<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PredictionService
{
    private $historyFile;
    private $pythonScript;

    public function __construct()
    {
        $this->historyFile = storage_path('app/predictions/history.csv');
        $this->pythonScript = base_path('predict.py');
    }

    /**
     * Genera nuevas predicciones basadas en datos actuales
     */
    public function generatePredictions($days = 7)
    {
        try {
            // Verificar que el script Python existe
            if (!file_exists($this->pythonScript)) {
                throw new \Exception('Script de predicción no encontrado: ' . $this->pythonScript);
            }

            // Verificar que hay datos históricos
            if (!file_exists($this->historyFile)) {
                throw new \Exception('Archivo de datos históricos no encontrado');
            }

            // Ejecutar script Python
            $process = new Process([
                'python3',
                $this->pythonScript,
                '--file', $this->historyFile,
                '--days', (string)$days
            ]);

            $process->setTimeout(30);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $predictions = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decodificando predicciones: ' . json_last_error_msg());
            }

            Log::info('Predicciones generadas exitosamente', [
                'days' => $days,
                'predictions_count' => count($predictions['predictions'] ?? [])
            ]);

            return [
                'success' => true,
                'predictions' => $predictions
            ];

        } catch (\Exception $e) {
            Log::error('Error generando predicciones', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el total de demanda de hoy
     */
    public function getTodayDemand()
    {
        try {
            if (!file_exists($this->historyFile)) {
                return 0;
            }

            $today = date('Y-m-d');
            $fp = fopen($this->historyFile, 'r');
            
            // Saltar encabezado
            fgetcsv($fp);

            $todayDemand = 0;
            while (($row = fgetcsv($fp)) !== false) {
                if (count($row) >= 2 && $row[0] === $today) {
                    $todayDemand = floatval($row[1]);
                    break;
                }
            }

            fclose($fp);
            return $todayDemand;

        } catch (\Exception $e) {
            Log::error('Error obteniendo demanda de hoy', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Obtiene estadísticas de demanda
     */
    public function getDemandStats()
    {
        try {
            if (!file_exists($this->historyFile)) {
                return [
                    'today' => 0,
                    'week' => 0,
                    'month' => 0,
                    'average' => 0,
                    'total_days' => 0
                ];
            }

            $data = [];
            $fp = fopen($this->historyFile, 'r');
            fgetcsv($fp); // Saltar encabezado

            while (($row = fgetcsv($fp)) !== false) {
                if (count($row) >= 2) {
                    $data[] = [
                        'date' => $row[0],
                        'demand' => floatval($row[1])
                    ];
                }
            }
            fclose($fp);

            $today = date('Y-m-d');
            $weekAgo = date('Y-m-d', strtotime('-7 days'));
            $monthAgo = date('Y-m-d', strtotime('-30 days'));

            $todayDemand = 0;
            $weekDemand = 0;
            $monthDemand = 0;
            $totalDemand = 0;

            foreach ($data as $row) {
                $totalDemand += $row['demand'];

                if ($row['date'] === $today) {
                    $todayDemand = $row['demand'];
                }
                if ($row['date'] >= $weekAgo) {
                    $weekDemand += $row['demand'];
                }
                if ($row['date'] >= $monthAgo) {
                    $monthDemand += $row['demand'];
                }
            }

            return [
                'today' => $todayDemand,
                'week' => $weekDemand,
                'month' => $monthDemand,
                'average' => count($data) > 0 ? $totalDemand / count($data) : 0,
                'total_days' => count($data)
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo estadísticas', ['error' => $e->getMessage()]);
            return [
                'today' => 0,
                'week' => 0,
                'month' => 0,
                'average' => 0,
                'total_days' => 0
            ];
        }
    }
}
