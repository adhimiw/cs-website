<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --stop-when-empty')->everyMinute();

Artisan::command('storage:link-safe {--target=} {--link=}', function () {
    $target = $this->option('target') ?: storage_path('app/public');
    $link = $this->option('link') ?: public_path('storage');

    // Normalize paths to avoid slash mismatches
    $target = rtrim(str_replace('\\', '/', $target), '/');
    $link = rtrim(str_replace('\\', '/', $link), '/');

    $this->info("Creating symlink from target: [$target] to link: [$link]");

    if (is_link($link)) {
        $currentSymlinkTarget = rtrim(str_replace('\\', '/', readlink($link)), '/');
        if ($currentSymlinkTarget === $target) {
            $this->info("The link path [$link] is already correctly connected to [$target].");
            return 0;
        }

        try {
            unlink($link);
        } catch (\Exception $e) {
            $this->error("The link path [$link] is a symlink pointing to [$currentSymlinkTarget], but it could not be deleted. Reason: " . $e->getMessage());
            return 1;
        }
    } elseif (file_exists($link)) {
        if (is_dir($link)) {
            $this->error("[$link] is an actual directory. Please delete or rename it manually first.");
            return 1;
        }
        
        try {
            unlink($link);
        } catch (\Exception $e) {
            $this->error("The file at [$link] could not be deleted. Reason: " . $e->getMessage());
            return 1;
        }
    }

    $parentDir = dirname($link);
    if (!is_dir($parentDir)) {
        mkdir($parentDir, 0755, true);
    }

    try {
        if (symlink($target, $link)) {
            $this->info("The [$link] link has been successfully connected to [$target].");
            return 0;
        } else {
            $this->error("Failed to create symlink from [$target] to [$link].");
            return 1;
        }
    } catch (\Exception $e) {
        $this->error("Failed to create symlink from [$target] to [$link]. Error: " . $e->getMessage());
        return 1;
    }
})->purpose('Create the symbolic links configured for the application using native PHP symlink');
