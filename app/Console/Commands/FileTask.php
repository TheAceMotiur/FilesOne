<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Upload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PlanHelper;

class FileTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:file-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check uploaded files for auto remove feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Upload::chunk(10, function (Collection $files) {
            foreach ($files as $file) {
                if (isset($file->autoremove) && $file->autoremove) {

                    $now = Carbon::now()->setTimezone('UTC');
                    $upload = Carbon::parse($file->created_at)
                        ->setTimezone('UTC');
                    $disk = $file->disk;

                    if ($file->autoremove == '5m') {
                        if ($upload->diffInMinutes($now) >= 5) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '30m') {
                        if ($upload->diffInMinutes($now) >= 30) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '1h') {
                        if ($upload->floatDiffInHours($now) >= 1) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '6h') {
                        if ($upload->floatDiffInHours($now) >= 6) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '12h') {
                        if ($upload->floatDiffInHours($now) >= 12) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '1d') {
                        if ($upload->floatDiffInDays($now) >= 1) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '1w') {
                        if ($upload->floatDiffInWeeks($now) >= 1) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    } elseif ($file->autoremove == '1m') {
                        if ($upload->floatDiffInMonths($now) >= 1) {
                            Storage::disk($disk)
                                ->delete($file->filename);
                            Upload::where('filename', $file->filename)
                                ->delete();
                        }
                    }
                }

                $autoDeletion = PlanHelper::autoDeletion($file->created_by_id);
                if ($autoDeletion && $autoDeletion > 0) {
                    $now = Carbon::now()->setTimezone('UTC');
                    $upload = Carbon::parse($file->created_at)
                        ->setTimezone('UTC');
                    $disk = $file->disk;

                    if ($upload->floatDiffInDays($now) >= $autoDeletion) {
                        Storage::disk($disk)
                            ->delete($file->filename);
                        Upload::where('filename', $file->filename)
                            ->delete();
                    }
                }
            }
        });
    }
}
