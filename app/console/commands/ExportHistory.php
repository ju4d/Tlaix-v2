<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\ConsumptionLog;
use Illuminate\Support\Facades\Storage;

class ExportHistory extends Command {
    protected $signature = 'export:history';
    protected $description = 'Export consumption logs to history.csv for predictions';

    public function handle(){
        $logs = ConsumptionLog::selectRaw('date, SUM(quantity) as demand')
                ->groupBy('date')->orderBy('date')->get();

        $csv = "date,demand\n";
        foreach($logs as $log){
            $csv .= $log->date . "," . $log->demand . "\n";
        }

        Storage::put('predictions/history.csv',$csv);
        $this->info("history.csv exported with ".$logs->count()." rows.");
    }
}
