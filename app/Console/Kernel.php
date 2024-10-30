<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\SriAutorizacionController;
use App\Http\Controllers\XmlController;
use App\Models\Sri_Autorizacion;
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {        
        
    }
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
        Commands\ReenvioDocumentos::class;
        //Commands\ReenvioDocumentos::class;
        
    }
}
