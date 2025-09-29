<?php
// app/Console/Commands/RecordDailyDemand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class RecordDailyDemand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demand:record {--date= : Fecha específica (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra la demanda diaria automáticamente basada en las órdenes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("📊 Registrando demanda para: " . $date->format('Y-m-d'));

        try {
            // Contar órdenes recibidas del día
            $ordersCount = Order::whereDate('date', $date)
                ->where('status', 'received')
                ->count();

            // Alternativamente, puedes contar platos vendidos
            // o cualquier otra métrica relevante para tu negocio

            if ($ordersCount > 0) {
                $this->recordDemand($date->format('Y-m-d'), $ordersCount);
                $this->info("✅ Demanda registrada: {$ordersCount} unidades");

                return Command::SUCCESS;
            } else {
                $this->warn("⚠️  No se encontraron órdenes para esta fecha");
                return Command::SUCCESS;
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Registra la demanda en el archivo CSV
     */
    private function recordDemand($date, $quantity)
    {
        $historyFile = storage_path('app/predictions/history.csv');

        // Asegurar que el directorio existe
        $directory = dirname($historyFile);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Leer datos existentes
        $data = $this->loadHistoryData($historyFile);

        // Buscar si ya existe el registro
        $found = false;
        foreach ($data as $key => $row) {
            if ($row['date'] === $date) {
                $data[$key]['demand'] = floatval($row['demand']) + floatval($quantity);
                $found = true;
                break;
            }
        }

        // Si no existe, agregar
        if (!$found) {
            $data[] = [
                'date' => $date,
                'demand' => floatval($quantity)
            ];
        }

        // Ordenar por fecha
        usort($data, function($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        // Guardar
        $fp = fopen($historyFile, 'w');
        fputcsv($fp, ['date', 'demand']);

        foreach ($data as $row) {
            fputcsv($fp, [$row['date'], $row['demand']]);
        }

        fclose($fp);
    }

    /**
     * Carga datos del CSV
     */
    private function loadHistoryData($file)
    {
        if (!file_exists($file)) {
            return [];
        }

        $data = [];
        $fp = fopen($file, 'r');

        // Saltar encabezado
        fgetcsv($fp);

        while (($row = fgetcsv($fp)) !== false) {
            if (count($row) >= 2) {
                $data[] = [
                    'date' => $row[0],
                    'demand' => floatval($row[1])
                ];
            }
        }

        fclose($fp);
        return $data;
    }
}
