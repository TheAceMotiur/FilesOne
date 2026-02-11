<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Artisan;
use Illuminate\Support\Facades\Hash;
use File;

class InstallerController extends Controller
{
    public function index()
    {
        return view('installer.index')
            ->with('functions', 'installer.function')
            ->with('page', '403')
            ->with('pageKey', '403');
    }

    public function installer(
        Request $request,
        string $step
    ) {
        sleep(1);

        if ($step == 'database') {

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:mysql,mariadb,pgsql,sqlsrv',
                'hostname' => 'required',
                'database' => 'required',
                'username' => 'required',
                'password' => 'nullable',
            ]);

            if ($validator->fails()) {
                return json_encode([
                    'result' => false,
                    'text' => 'The form data is missing or incorrect.',
                    'errors'=> $validator->errors(),
                ]);
            }

            $formData = [
                'db-type' => $request->input('type'),
                'db-host' => $request->input('hostname'),
                'db-name' => $request->input('database'),
                'db-user' => $request->input('username'),
                'db-pass' => $request->input('password'),
            ];
            $action = $this->databaseCheck($formData);

            return json_encode([
                'result' => $action['result'] ?? '',
                'text' => $action['text'] ?? '',
                'errors' => $action['errors'] ?? '',
                'step' => $action['step'] ?? '',
            ]);

        } elseif ($step == 'settings') {

            $validator = Validator::make($request->all(), [
                'website-url' => 'required|url:http,https',
                'admin-email' => 'required|email|max:100',
                'admin-password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return json_encode([
                    'result' => false,
                    'text' => 'The form data is missing or incorrect.',
                    'errors'=> $validator->errors(),
                ]);
            }

            $formData = [
                'website-url' => $request->input('website-url'),
                'admin-email' => $request->input('admin-email'),
                'admin-password' => $request->input('admin-password'),
            ];
            session(['settings' => $formData]);

            return json_encode([
                'result' => true,
                'text' => 'The settings are saved, you can continue.',
            ]);
            
        } elseif ($step == 'finish') {

            $step = $request->input('step');
            if (!in_array($step, ['requirements','database','finish'])) {
                return json_encode([
                    'result' => false,
                    'text' => 'An unknown error occurred, try again.',
                ]);
            }
            $action = $this->finish($step);

            return json_encode([
                'result' => $action['result'],
                'text' => $action['text'] ?? '',
                'errors' => $action['errors'] ?? '',
                'step' => $action['step'] ?? '',
                'finish' => $action['finish'] ?? '',
            ]);

        }

        return json_encode([
            'result' => false,
            'text' => 'An unknown error occurred, try again later.',
            'errors' => '',
        ]);
    }

    private function databaseCheck(
        array $formData
    ) {
        if (
            $formData['db-type'] == 'mysql' 
            || $formData['db-type'] == 'mariadb'
        ) {
            config([
                'database.connections.mysql.host' => $formData['db-host'],
                'database.connections.mysql.port' => '3306',
                'database.connections.mysql.database' => $formData['db-name'],
                'database.connections.mysql.username' => $formData['db-user'],
                'database.connections.mysql.password' => $formData['db-pass'],
            ]);

            try {
                DB::purge('mysql');
                DB::connection('mysql')->getPdo();
                session(['database' => $formData]);
                return [
                    'result' => true,
                    'text' => 'Connection to the database has been established.',
                ];

            } catch (\Exception $e) {
                return [
                    'result' => false,
                    'text' => 'Connection to the database could not be established.',
                ];
            }


        } elseif ($formData['db-type'] == 'pgsql') {

            config([
                'database.connections.pgsql.host' => $formData['db-host'],
                'database.connections.pgsql.port' => '5432',
                'database.connections.pgsql.database' => $formData['db-name'],
                'database.connections.pgsql.username' => $formData['db-user'],
                'database.connections.pgsql.password' => $formData['db-pass'],
            ]);

            try {
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                session(['database' => $formData]);
                return [
                    'result' => true,
                    'text' => 'Connection to the database has been established.',
                ];

            } catch (\Exception $e) {

                return [
                    'result' => false,
                    'text' => 'Connection to the database could not be established.',
                ];

            }

        } elseif ($formData['db-type'] == 'sqlsrv') {

            config([
                'database.connections.sqlsrv.host' => $formData['db-host'],
                'database.connections.sqlsrv.port' => '1433',
                'database.connections.sqlsrv.database' => $formData['db-name'],
                'database.connections.sqlsrv.username' => $formData['db-user'],
                'database.connections.sqlsrv.password' => $formData['db-pass'],
            ]);

            try {
                DB::purge('sqlsrv');
                DB::connection('sqlsrv')->getPdo();
                session(['database' => $formData]);
                return [
                    'result' => true,
                    'text' => 'Connection to the database has been established.',
                ];

            } catch (\Exception $e) {

                return [
                    'result' => false,
                    'text' => 'Connection to the database could not be established.',
                ];

            }

        }

        return [
            'result' => false,
            'text' => 'Connection to the database could not be established.',
        ];
    }

    private function settings(
        array $formData
    ) {
        session(['settings' => $formData]);
    }

    private function finish($step) {

        if ($step == 'requirements') {

            $requirements = $this->requirements();
            if ($requirements['result']) {
                return [
                    'result' => true,
                    'step' => 'database',
                ];
            } else {
                return [
                    'result' => false,
                    'text' => $requirements['text'],
                    'errors' => $requirements['errors'],
                ];
            }
        }

        if ($step == 'database') {

            $setup = $this->setup();
            if ($setup['result']) {
                return [
                    'result' => true,
                    'step' => 'finish',
                ];
            } else {
                return [
                    'result' => false,
                    'text' => $setup['text'],
                ];
            }
        }

        if ($step == 'finish') {
            $finish = $this->regenerateKey();
            if ($finish['result']) {

                $finish = $this->lastStep();

                if ($finish['result']) {
                    return [
                        'result' => true,
                        'text' => 'Installation is finished. Congratulations.',
                        'finish' => true,
                    ];
                } else {
                    return [
                        'result' => false,
                        'text' => $finish['text'],
                    ];
                }
            } else {
                return [
                    'result' => false,
                    'text' => $finish['text'],
                ];
            }
        }

        return [
            'result' => false,
            'text' => 'An unknown error occurred, try again.',
        ];
    }

    private function requirements()
    {
        if (
            !version_compare(phpversion(), '8.2', '>=')
            || !extension_loaded('ctype')
            || !extension_loaded('curl')
            || !extension_loaded('dom')
            || !extension_loaded('fileinfo')
            || !extension_loaded('filter')
            || !extension_loaded('hash')
            || !extension_loaded('mbstring')
            || !extension_loaded('openssl')
            || !extension_loaded('pcre')
            || !extension_loaded('pdo')
            || !extension_loaded('session')
            || !extension_loaded('tokenizer')
            || !extension_loaded('xml')
        ) {
            $errors = [];
            if (!version_compare(phpversion(), '8.2', '>=')) {
                array_push($errors, 'Php version must be at least 8.2');
            }
            if (!extension_loaded('ctype')) {
                array_push($errors, 'Ctype PHP extension not found.');
            }
            if (!extension_loaded('curl')) {
                array_push($errors, 'cURL PHP extension not found.');
            }
            if (!extension_loaded('dom')) {
                array_push($errors, 'DOM PHP extension not found.');
            }
            if (!extension_loaded('fileinfo')) {
                array_push($errors, 'Fileinfo PHP extension not found.');
            }
            if (!extension_loaded('filter')) {
                array_push($errors, 'Filter PHP extension not found.');
            }
            if (!extension_loaded('hash')) {
                array_push($errors, 'Hash PHP extension not found.');
            }
            if (!extension_loaded('mbstring')) {
                array_push($errors, 'Mbstring PHP extension not found.');
            }
            if (!extension_loaded('openssl')) {
                array_push($errors, 'OpenSSL PHP extension not found.');
            }
            if (!extension_loaded('pcre')) {
                array_push($errors, 'PCRE PHP extension not found.');
            }
            if (!extension_loaded('pdo')) {
                array_push($errors, 'PDO PHP extension not found.');
            }
            if (!extension_loaded('session')) {
                array_push($errors, 'Session PHP extension not found.');
            }
            if (!extension_loaded('tokenizer')) {
                array_push($errors, 'Tokenizer PHP extension not found.');
            }
            if (!extension_loaded('xml')) {
                array_push($errors, 'XML PHP extension not found.');
            }

            return [
                'result' => false,
                'text' => 'Your server does not meet the required conditions.',
                'errors' => $errors,
            ];
      
        } else {
    
            return [
                'result' => true,
                'step' => 'database',
            ];
        }
    }

    private function setup() 
    {
        $database = session('database');
        $settings = session('settings');

        if (
            is_null($database)
            || is_null($database['db-type']) 
            || is_null($database['db-host']) 
            || is_null($database['db-name']) 
            || is_null($database['db-user']) 
        ) {
            return [
                'result' => false,
                'text' => 'The form data is missing or incorrect.',
            ];
        }

        if (
            is_null($settings)
            || is_null($settings['website-url']) 
            || is_null($settings['admin-email']) 
            || is_null($settings['admin-password']) 
        ) {
            return [
                'result' => false,
                'text' => 'The form data is missing or incorrect.',
            ];
        }

        $envFile = base_path('resources/views/installer/.env');
        $envFileTarget = base_path('.env');
        if (!file_exists($envFile)) {
            return [
                'result' => false,
                'text' => 'Env file not found.',
            ];
        }

        $envFileContent = file_get_contents($envFile);
        
        $new = str_replace("%base_url%", $settings['website-url'], $envFileContent);
        $new = str_replace("%db_type%", $database['db-type'], $new);
        $new = str_replace("%db_host%", $database['db-host'], $new);
        if ($database['db-type'] == 'mysql' || $database['db-type'] == 'mariadb') {
            $new = str_replace("%db_port%", '3306', $new); 
        } elseif ($database['db-type'] == 'pgsql') {
            $new = str_replace("%db_port%", '5432', $new); 
        } elseif ($database['db-type'] == 'sqlsrv') {
            $new = str_replace("%db_port%", '1433', $new); 
        } else {
            $new = str_replace("%db_port%", '3306', $new); 
        }
        $new = str_replace("%db_name%", $database['db-name'], $new);
        $new = str_replace("%db_username%", $database['db-user'], $new);
        if (isset($database['db-pass']) && $database['db-pass']) {
            $new = str_replace("%db_password%", '"'.$database['db-pass'].'"', $new);
        }

        $handle = fopen($envFileTarget, 'w+');
        if (is_writable($envFileTarget)) {
            if (fwrite($handle, $new)) {
              
                //Artisan::call('cache:clear');
                //Artisan::call('config:clear');
                //Artisan::call('view:clear');
                //Artisan::call('route:clear');

                config(['database.default' => $database['db-type']]);
                config(["database.connections.{$database['db-type']}.host" => $database['db-host']]);
                config(["database.connections.{$database['db-type']}.database" => $database['db-name']]);
                config(["database.connections.{$database['db-type']}.username" => $database['db-user']]);
                config(["database.connections.{$database['db-type']}.password" => $database['db-pass']]);

                sleep(1);

                try {

                    Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
                    Artisan::call('migrate', ['--force' => true]);

                    sleep(1);

                    $adminAccount = DB::table('users')
                        ->where('id', 1)
                        ->update([
                            'email' => $settings['admin-email'],
                            'password' => Hash::make($settings['admin-password']),
                        ]);
                    if ($adminAccount) {
                        return [
                            'result' => true,
                        ];
                    }

                    $this->reset();
                    return [
                        'result' => false,
                        'text' => 'Failed to create admin account.',
                    ];

                } catch (\Throwable $th) {
                    $this->reset();
                    $error = substr($th, 0, 100);
                    return [
                        'result' => false,
                        'text' => 'An unknown error occurred, try again.'
                            . " ({$error}...)",
                    ];
                }

            } else {
                $this->reset();
                return [
                    'result' => false,
                    'text' => 'Failed to write data to env file.',
                ];
            }
        } else {
            $this->reset();
            return [
                'result' => false,
                'text' => 'The env file is not writable.',
            ];
        }
    }

    private function regenerateKey() 
    {
        try {
            Artisan::call('key:generate', ['--force' => true]);

            sleep(1);

            return [
                'result' => true,
            ];

        } catch (\Throwable $th) {
            return [
                'result' => false,
                'text' => 'Failed to run artisan command (key).',
            ];
        }
    }

    private function lastStep() 
    {
        $envFile = base_path('.env');
        if (!file_exists($envFile)) {
            return [
                'result' => false,
                'text' => 'Base .env file not found.',
            ];
        }

        $envFileContent = file_get_contents($envFile);
        
        $new = str_replace("%not_installed%", '%installed%', $envFileContent);

        $handle = fopen($envFile, 'w+');
        if (is_writable($envFile)) {
            if (fwrite($handle, $new)) {

                //Artisan::call('config:cache');
                //Artisan::call('config:clear');
                
                return [
                    'result' => true,
                ];

            } else {
                $this->reset();
                return [
                    'result' => false,
                    'text' => 'Failed to write data to env file.',
                ];
            }
        } else {
            $this->reset();
            return [
                'result' => false,
                'text' => 'The env file is not writable.',
            ];
        }

    }

    private function reset() 
    {
        Artisan::call('migrate:rollback', ['--force' => true]);
        $envFile = base_path('.env');
        @unlink($envFile);
        $envBaseFile = base_path('.env.base');
        $envNewFile = base_path('.env');
        sleep(1);
        File::copy(
            $envBaseFile, 
            $envNewFile
        );
    }

}
