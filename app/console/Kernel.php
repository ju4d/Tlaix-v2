<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ExportHistory; // 👈 importar el comando

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ExportHistory::class, // 👈 registrar el comando
    ];

    protected function schedule(Schedule $schedule)
    {
        // Aquí podrías programar ejecución automática
        // $schedule->command('export:history')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
