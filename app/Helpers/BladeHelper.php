<?php

use Carbon\Carbon;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Helpers\PlanHelper;
use Illuminate\Support\Facades\DB;

/**
 * Load css and js files
 * @param string $url
 * @param mixed $exludeDefer
 * @return string
 */
function library(
    string $url,
    mixed $exludeDefer = false,
): string {
    $fullUrl = url($url);
    $type = pathinfo($url);
    if ($type['extension'] == 'css') {

        if (setting('defer') == 1 && !$exludeDefer) {
            $onload = 'this.onload=null;this.rel="stylesheet"';
            $lib = "<link rel='preload' href='{$fullUrl}' as='style' "
                . "onload='{$onload}'>"
                . "<noscript><link rel='stylesheet' href='{$fullUrl}'>"
                . "</noscript>";
        } else {
            $lib = '<link rel="stylesheet" href="' . $fullUrl . '">';
        }
    } elseif ($type['extension'] == 'js') {

        if (setting('defer') == 1 && !$exludeDefer) {
            $lib = "<script src='{$fullUrl}' defer></script>";
        } else {
            $lib = "<script src='{$fullUrl}'></script>";
        }
    }

    return $lib;
}

/**
 * Get uploadable file types
 * @param mixed $key
 * @return mixed
 */
function uploadableTypes(mixed $return = false): mixed {
    $types = config('upload.UPLOADABLE_TYPES');
    if ($return == 'array') {
        return explode(',', $types);
    }
    return $types;
}

/**
 * Get uploadable file types
 * @param mixed $key
 * @return mixed
 */
function uploadableMimeTypes(mixed $return = false): mixed {
    $types = config('upload.UPLOADABLE_TYPES');
    $typesArr = explode(',', $types);

    $mimesArr = [];
    foreach ($typesArr as $value) {
        if (config("mimetypes.{$value}")) {
            array_push($mimesArr, config("mimetypes.{$value}"));
        }
    }

    if ($return == "array") {
        return $mimesArr;
    }

    return implode(",", $mimesArr);
}

/**
 * Get convertible file types
 * @param mixed $key
 * @return mixed
 */
function convertibleTypes(mixed $return = false): mixed {
    $types = config('upload.CONVERTIBLE_TYPES');
    if ($return == 'array') {
        return explode(',', $types);
    }
    return $types;
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function setting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('setting')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}

/**
 * Check an image and give full path
 * @param string $path
 * @param mixed $img
 * @param mixed $size
 * @return mixed
 */
function img(
    string $path,
    mixed $img = false,
    mixed $size = false
): mixed {
    if ($img) {
        if (file_exists(public_path("uploads/img/{$path}/{$img}"))) {
            return url("uploads/img/{$path}/{$img}");
        } else {
            return ($size == "lg")
                ? url("/assets/image/404-default-lg.webp")
                : url("/assets/image/404-default-sm.webp");
        }
    }

    if ($path == "user") {
        return ($size == "lg")
            ? url("/assets/image/user-default-lg.webp")
            : url("/assets/image/user-default-sm.webp");
    }

    if ($path == "blog") {
        return ($size == "lg")
            ? url("/assets/image/blog-default-lg.webp")
            : url("/assets/image/blog-default-sm.webp");
    }

    if ($path == "system") {
        return ($size == "lg")
            ? url("/assets/image/system-default-lg.webp")
            : url("/assets/image/system-default-sm.webp");
    }

    return ($size == "lg")
        ? url("/assets/image/404-default-lg.webp")
        : url("/assets/image/404-default-sm.webp");
}

/**
 * Activity in the sidebar in the panels
 * @param string $key
 * @param array $keys
 * @param mixed $collapse
 * @return string
 */
function active(
    string $key,
    array $keys,
    mixed $collapse = false
): string {
    return $collapse
        ? (in_array($key, $keys) ? ' show' : '')
        : (in_array($key, $keys) ? ' active' : '');
}

/**
 * Generate seo tags for the pages
 * @param array $seoData
 * @return string
 */
function seoBlock(
    array $seoData
): string {
    $seoString = '';
    $currentUrl = url()->current();
    $websiteName = setting('name');
    $favicon = setting('favicon')
        ? img('other', setting('favicon'))
        : false;

    $seoString .= isset($seoData['title']) && $seoData['title']
        ? "<title>{$seoData['title']}</title>" . PHP_EOL
        : "<title></title>" . PHP_EOL;

    $seoString .= isset($seoData['description']) && $seoData['description']
        ? "<meta name='description' "
        . "content='{$seoData['description']}'>" . PHP_EOL
        : "<meta name='description' content=''>" . PHP_EOL;

    $seoString .= isset($seoData['keywords']) && $seoData['keywords']
        ? "<meta name='keywords' content='{$seoData['keywords']}'>" . PHP_EOL
        : "<meta name='keywords' content=''>" . PHP_EOL;

    $seoString .= isset($seoData['og_title']) && $seoData['og_title']
        ? "<meta property='og:title' "
        . "content='{$seoData['og_title']}'>" . PHP_EOL
        : "<meta property='og:title' content=''>" . PHP_EOL;

    $seoString .= (
        isset($seoData['og_description'])
        && $seoData['og_description']
    )
        ? "<meta property='og:description' "
        . "content='{$seoData['og_description']}'>" . PHP_EOL
        : "<meta property='og:description' content=''>" . PHP_EOL;

    $seoString .= isset($seoData['og_image']) && $seoData['og_image']
        ? "<meta property='og:image' "
        . "content='{$seoData['og_image']}'>" . PHP_EOL
        : "";

    $seoString .= isset($seoData['no_index']) && $seoData['no_index']
        ? "<meta name='robots' content='{$seoData['no_index']}'>" . PHP_EOL
        : "<meta name='robots' content='index, follow'>" . PHP_EOL;

    $seoString .= "<meta property='og:url' content='{$currentUrl}'>" . PHP_EOL;

    $seoString .= "<meta property='og:site_name' "
        . "content='{$websiteName}'>" . PHP_EOL;

    $seoString .= "<meta name='twitter:card' content='summary'>" . PHP_EOL;

    $seoString .= isset($seoData['og_title']) && $seoData['og_title']
        ? "<meta name='twitter:title' "
        . "content='{$seoData['og_title']}'>" . PHP_EOL
        : "<meta name='twitter:title' content=''>" . PHP_EOL;

    $seoString .= (
        isset($seoData['og_description'])
        && $seoData['og_description']
    )
        ? "<meta name='twitter:description' "
        . "content='{$seoData['og_description']}'>" . PHP_EOL
        : "<meta name='twitter:description' content=''>" . PHP_EOL;

    $seoString .= isset($seoData['og_image']) && $seoData['og_image']
        ? "<meta name='twitter:image' "
        . "content='{$seoData['og_image']}'>" . PHP_EOL
        : "";

    $seoString .= "<link rel='canonical' href='{$currentUrl}'>" . PHP_EOL;

    $seoString .= "<link rel='Shortcut icon' href='{$favicon}'>" . PHP_EOL;

    return $seoString;
}

/**
 * Generate panel page names
 * @return mixed
 */
function pageName(
    array $names
): mixed {
    if (count($names) > 1) {
        $namesArr = array_map('ucwords', $names);
        $namesStr = implode(" - ", $namesArr);

        return $namesStr;
    }

    return $names[0];
}

/**
 * Get user data
 * @param mixed $key
 * @return mixed
 */
function userData(
    mixed $key = false
): mixed {
    if (Auth::check()) {
        if ($key) {
            return Auth::user()->$key ?? false;
        } else {
            return Auth::user();
        }
    }
    return false;
}

/**
 * Helper function for langer
 * @param array $matches
 * @return mixed
 */
function add_lang(array $matches): mixed
{
    $variable = str_replace(
        array('[lang]', '[/lang]'),
        array('', ''),
        $matches[0]
    );

    return __($variable);
}

/**
 * Get value from cached db
 * @param string $page
 * @param string $widget
 * @param string $key
 * @param bool $translate
 * @return mixed
 */
function widget(
    string $page,
    string $widget,
    string $key,
    bool $translate = true
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        if (
            $value = Cache::get('pages')
                ->where('page_key', $page)
                ->first()->content
        ) {
            $content = json_decode($value, true);
            $content = $content[$widget][$key] ?? '';

            if ($translate) {
                $pattern = '/\[lang\](.*?)\[\/lang\]/ism';
                $result = preg_replace_callback($pattern, 'add_lang', $content);
    
                return $result;
            }

            return $content;
        }
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function emailContent(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('emailContent')
            ->where('name', $key)
            ->first()->content ?? '';
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @param bool $translate
 * @return mixed
 */
function footerSettings(
    string $key,
    bool $translate = true
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        $content = Cache::get('footerSettings')
            ->where('name', $key)
            ->first()->value ?? '';

        if ($translate) {
            $pattern = '/\[lang\](.*?)\[\/lang\]/ism';
            $result = preg_replace_callback($pattern, 'add_lang', $content);

            return $result;
        }

        return $content;
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function storageSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('storageSetting')
            ->where('id', $key)
            ->first() ?? '';
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function defaultStorage(): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('storageSetting')
            ->where('default', 1)
            ->first();
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function affiliateSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('affiliateSetting')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}

/**
 * Get given date and set format
 * @param mixed $date
 * @param mixed $format
 * @param mixed $humanize
 * @return string
 */
function dateFormat(
    mixed $date = false,
    mixed $format = false,
    mixed $humanize = false
): mixed {
    $timezone = setting('time_zone');
    $date = $date
        ? Carbon::parse($date)
            ->locale(LaravelLocalization::getCurrentLocale())
            ->setTimezone(
                isset($timezone) && $timezone 
                    ? $timezone 
                    : 'Etc/Greenwich'
            )
        : Carbon::now()
            ->locale(LaravelLocalization::getCurrentLocale())
            ->setTimezone(
                isset($timezone) && $timezone 
                    ? $timezone 
                    : 'Etc/Greenwich'
            );

    if ($humanize) {
        return $date->diffForHumans();
    }

    if ($format) {
        return $date->translatedFormat($format);
    }

    return setting('time_format') == 1
        ? $date->translatedFormat('Y-m-d g:i A')
        : $date->translatedFormat('Y-m-d H:i');
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function paymentSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('paymentSetting')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}

/**
 * Get value from cached db
 * @return mixed
 */
function fileReportsCount(): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('fileReportsCount') ?? 0;
    }

    return 0;
}

/**
 * Add report number to sidebar
 * @return mixed
 */
function fileReportsNotifier(): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        $reports = fileReportsCount();
        $html = '';
        if ($reports > 0) {
            $html = "<span class='notification-icon'>{$reports}</span>";
        }

        return $html;
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $page
 * @param mixed $slash
 * @return mixed
 */
function pageSlug(
    string $page,
    mixed $slash = false,
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        $pageData = Cache::get('pages')
            ->where('page_key', $page)
            ->first();

        return isset($pageData->url) && $pageData->url
            ? ($slash ? "/{$pageData->url}" : $pageData->url)
            : '';
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function emailSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('emailSetting')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}



/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function langSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('langSetting')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}

/**
 * Get value from cached db
 * @param string $key
 * @return mixed
 */
function downloadSetting(
    string $key
): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('downloadSettings')
            ->where('name', $key)
            ->first()->value ?? '';
    }

    return '';
}

/**
 * Get value from cached db
 * @param mixed $status
 * @return mixed
 */
function languages(): mixed {
    if (
        env('INSTALLED') == '%installed%' 
        && !request()->routeIs('install')
    ) {
        return Cache::get('languages');
    }

    return '';
}

/**
 * Get value from cached db
 * @param int $bytes
 * @param string $target
 * @return mixed
 */
function formatBytes2(
    int $bytes, 
    string $target
): string {
    if ($target == 'kb') {
        return round($bytes / 1024, 2) . ' KB';
    } elseif ($target == 'mb') {
        return round($bytes / (1024 * 1024), 2) . ' MB';
    } elseif ($target == 'gb') {
        return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
    } else {
        return round($bytes, 2) . ' B';
    }
}

/**
 * Get value from cached db
 * @param int $bytes
 * @param int $precision
 * @return mixed
 */
function formatBytes(
    int $bytes, 
    int $precision = 2
): string {
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    
    if ($bytes < $kilobyte) {
        if ($bytes == 0) {
            return 0;
        }
        return $bytes . ' B';
    } elseif ($bytes < $megabyte) {
        return round($bytes / $kilobyte, $precision) . ' KB';
    } elseif ($bytes < $gigabyte) {
        return round($bytes / $megabyte, $precision) . ' MB';
    } else {
        return round($bytes / $gigabyte, $precision) . ' GB';
    }
}

/**
 * Get value from cached db
 * @param mixed $kilobyte
 * @param int $precision
 * @return mixed
 */
function formatKiloBytes(
    mixed $kilobyte, 
    int $precision = 1
): string {
    if (gettype($kilobyte) == 'integer') {
        $megabyte = $kilobyte / 1024;
        
        if ($kilobyte > 999) {
            return round($megabyte, $precision) . ' MB';  
        }

        return $megabyte . ' MB';
    }

    return "0 MB";
}

/**
 * Get value from cached db
 * @param int $megabyte
 * @param int $precision
 * @return mixed
 */
function formatMegaBytes(
    int $megabyte, 
    int $precision = 1
): string {
    $gigabyte = $megabyte / 1024;
    
    if ($megabyte > 999) {
        return round($gigabyte, $precision) . ' GB';  
    }

    return $megabyte . ' MB';
}
    
/**
 * Return content summary
 * @param string $content
 * @param int $limit
 * @param mixed $stop
 * @param mixed $removeTags
 * @return string
 */
function textSummary(
    string $content,
    int $limit,
    mixed $stop = false,
    mixed $removeTags = false
): mixed {
    if ($removeTags) {
        $content = strip_tags($content);
    }
    $length = Str::length($content);

    if ($length > $limit) {
        $text = Str::substr($content, 0, $limit);
        return $stop ? $text . $stop : $text;
    } else {
        return $content;
    }
}

/**
 * Manipulate html element (Add css classes to an element)
 * @param $html
 * @param $addClass
 * @return string
 */
function classList(
    string $html,
    array $addClass
): bool|string {
    $singleQuotes = substr_count($html, '"') == 2
        ? true
        : false;
    $doubleQuotes = substr_count($html, "'") == 2
        ? true
        : false;

    if ($singleQuotes) {
        $htmlArr = explode('"', $html);
    } elseif ($doubleQuotes) {
        $htmlArr = explode("'", $html);
    } else {
        return false;
    }

    $newString = "";
    foreach ( $addClass as $class ) {
        $newString .= " $class";
    }

    return "{$htmlArr[0]}'{$htmlArr[1]}{$newString}'{$htmlArr[2]}";
}

/**
 * Get user's plan
 * @return mixed
 */
function myPlan(): mixed
{
    return PlanHelper::myPlan(Auth::id());
}

/**
 * Generate notice page
 * @param mixed $title
 * @param mixed $text
 * @return mixed
 */
function notice(
    mixed $title,
    mixed $text
): mixed {
    return view('frontend.notice.index')
        ->with('functions', 'frontend.notice.function')
        ->with('page', 'custom')
        ->with('pageKey', '404')
        ->with('title', $title)
        ->with('text', $text);
}

/**
 * Get convertible file types
 * @param mixed $extension
 * @return bool
 */
function canPreviewed(mixed $extension): bool {
    if (isset($extension) && $extension) {
        $previews = config('upload.PREVIEW_TRUE');
        $previewsArr = explode(',', $previews);
    
        if (!in_array($extension, $previewsArr)) {
            return false;
        }
        return true;
    }
    return false;
}

/**
 * Generate html code of a file
 * @param string $fileKey
 * @return mixed
 */
function fileHtml(
    string $fileKey,
): string {
    $fileSlug = pageSlug('file') ?: 'file';
    $fileUrl = url("{$fileSlug}/{$fileKey}");
    $text = __('lang.download');

    return "<a href='{$fileUrl}'>{$text}</a>";
}

/**
 * Generate bb code of a file
 * @param string $fileKey
 * @return mixed
 */
function fileBBCode(
    string $fileKey,
): string {
    $fileSlug = pageSlug('file') ?: 'file';
    $fileUrl = url("{$fileSlug}/{$fileKey}");
    $text = __('lang.download');

    return "[url={$fileUrl}]{$text}[/url]";
}

/**
 * Return uploadable file types as js array
 * @return mixed
 */
function uploadableTypesJs(): string {
    $types = config('upload.UPLOADABLE_TYPES');
    $typesArr = explode(',', $types);

    return json_encode($typesArr);
}

/**
 * Generate code (barcode) for records
 * @param string $dbTable
 * @param string $column
 * @param int $length
 * @return mixed
 */
function generateCode(
    string $dbTable,
    string $column,
    int $length
): mixed {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersNumber = strlen($characters);
    $codeLength = $length;

    $code = '';

    while (strlen($code) < $codeLength) {
        $position = rand(0, $charactersNumber - 1);
        $character = $characters[$position];
        $code = $code . $character;
    }

    if (DB::table($dbTable)->where($column, $code)->exists()) {
        generateCode($dbTable, $column, $length);
    }

    $key = implode(
        '-',
        str_split($code, 4)
    );

    return $key;
}

/**
 * Get file extension from file name
 * @param string $filename
 * @return string
 */
function fileExtension(
    string $filename,
): string {
    $filenameArr = explode('.', $filename);
    return $filenameArr[1];
}

/**
 * Censor email for affiliate table
 * @param string $email
 * @return string
 */
function censorEmail(
    string $email,
): string {
    if (str_contains($email,'@')) {
        $emailArr = explode('@', $email);
        $name = $emailArr[0];
        $domain = explode('.', $emailArr[1]);

        return substr($name, 0, 2) 
            . '****@' 
            . substr($domain[0], 0, 2) 
            . '****.' 
            . $domain[1];
    }

    return $email;
}

/**
 * Generate unique db code
 * @param string $dbTable
 * @param string $dbColumn
 * @return mixed
 */
function uniqueCode(
    $dbTable, 
    $dbColumn
): mixed {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersNumber = strlen($characters);
    $codeLength = 4;

    $userToken = [];

    for ($i = 0; $i <= 3; $i++) {
        $code = '';
        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }
        $userToken[$i] = $code;
    }

    $userToken = implode('-', $userToken);

    if (DB::table($dbTable)->where($dbColumn, $userToken)->exists()) {
        return uniqueCode($dbTable, $dbColumn);
    }

    return $userToken;
}

/**
 * Generate unique db code for the shorter
 * @param string $dbTable
 * @param string $dbColumn
 * @return mixed
 */
function shortKey(
    $dbTable, 
    $dbColumn,
    $length = 6
): mixed {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz'
        . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersNumber = strlen($characters);
    $codeLength = $length;


    $code = '';
    while (strlen($code) < $codeLength) {
        $position = rand(0, $charactersNumber - 1);
        $character = $characters[$position];
        $code = $code.$character;
    }

    if (DB::table($dbTable)->where($dbColumn, $code)->exists()) {
        return uniqueCode($dbTable, $dbColumn);
    }

    return $code;
}

/**
 * Get countdown time in seconds
 * @return int
 */
function countdownTime(): int {
    if (Auth::check()) {
        $plan = PlanHelper::myPlan(Auth::id());
        if (
            isset($plan['features']['countdown']) 
            && $plan['features']['countdown'] == 1
        ) {
            return 0;
        }
        return intval(downloadSetting('countdown'));
    }
    return intval(downloadSetting('countdown'));
}