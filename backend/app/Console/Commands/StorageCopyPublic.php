<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageCopyPublic extends Command
{
    protected $signature = 'storage:copy-public
                            {--from= : Source directory (defaults to storage/app/public)}
                            {--to= : Destination directory (defaults to PUBLIC_STORAGE_PATH or public/storage)}';

    protected $description = 'Copy files from storage/app/public to public_html/storage (for hosts that disable symlink)';

    public function handle(): int
    {
        $from = $this->option('from') ?: storage_path('app/public');
        $to   = $this->option('to')   ?: env('PUBLIC_STORAGE_PATH', public_path('storage'));

        if (!is_dir($from)) {
            $this->error("Source directory does not exist: $from");
            return 1;
        }

        if (!is_dir($to)) {
            mkdir($to, 0755, true);
            $this->info("Created destination directory: $to");
        }

        $copied = 0;
        $skipped = 0;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = substr($item->getPathname(), strlen($from) + 1);
            $destPath = $to . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                // Only copy if file doesn't exist or source is newer
                if (!file_exists($destPath) || filemtime($item->getPathname()) > filemtime($destPath)) {
                    copy($item->getPathname(), $destPath);
                    $copied++;
                } else {
                    $skipped++;
                }
            }
        }

        $this->info("Done! Copied: $copied files, Skipped (up-to-date): $skipped files.");
        $this->info("From: $from");
        $this->info("To:   $to");

        return 0;
    }
}
