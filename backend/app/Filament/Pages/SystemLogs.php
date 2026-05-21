<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Filament\Notifications\Notification;

class SystemLogs extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'System Logs';

    protected static ?string $title = 'System & Application Logs';

    protected string $view = 'filament.pages.system-logs';

    public $logContent = '';

    public function mount()
    {
        $this->readLogs();
    }

    public function readLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            // Read last 200 lines
            $file = file($logPath);
            $lines = array_slice($file, -200);
            $this->logContent = implode("", $lines);
        } else {
            $this->logContent = 'No system log entries found.';
        }
    }

    public function clearLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            $this->readLogs();
            
            Notification::make()
                ->success()
                ->title('Logs cleared successfully')
                ->send();
        }
    }
}
