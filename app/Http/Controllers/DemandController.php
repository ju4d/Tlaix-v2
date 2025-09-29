<?php
// app/Http/Controllers/DemandController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DemandController extends Controller
{
    private $historyFile;

    public function __construct()
    {
        $this->historyFile = storage_path('app/predictions/history.csv');
    }

    /**
     * Registra una nueva venta/demanda
     */
    public function recordDemand(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0'
        ]);

        try {
            $date = Carbon::parse($validated['date'])->format('Y-m-d');
            $quantity = floatval($validated['quantity']);

            // Actualizar archivo CSV
            $this->updateHistoryFile($date, $quantity);

            Log::info('Demanda registrada', [
                'date' => $date,
                'quantity' => $quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Demanda registrada correctamente',
                'date' => $date,
                'quantity' => $quantity
            ]);

        } catch (\Exception $e) {
            Log::error('Error registrando demanda', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al registrar demanda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el resumen de demanda actual
     */
    public function getDemandSummary()
    {
        try {
            $data = $this->loadHistoryData();

            $today = Carbon::today()->format('Y-m-d');
            $todayDemand = 0;
            $weekDemand = 0;
            $monthDemand = 0;

            foreach ($data as $row) {
                $rowDate = Carbon::parse($row['date']);
                $demand = floatval($row['demand']);

                if ($row['date'] === $today) {
                    $todayDemand += $demand;
                }

                if ($rowDate->isCurrentWeek()) {
                    $weekDemand += $demand;
                }

                if ($rowDate->isCurrentMonth()) {
                    $monthDemand += $demand;
                }
            }

            return response()->json([
                'today' => $todayDemand,
                'week' => $weekDemand,
                'month' => $monthDemand,
                'average_daily' => count($data) > 0 ? array_sum(array_column($data, 'demand')) / count($data) : 0,
                'total_records' => count($data),
                'last_update' => count($data) > 0 ? $data[count($data) - 1]['date'] : null
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Registra automáticamente la demanda diaria basada en ventas
     */
    public function autoRecordDailyDemand()
    {
        try {
            $today = Carbon::today();

            // Aquí puedes obtener la demanda real de tu sistema
            // Por ejemplo, contar órdenes completadas del día
            $todayOrders = \App\Models\Order::whereDate('created_at', $today)
                ->where('status', 'received')
                ->count();

            // O contar platos vendidos
            // $todayDishes = DB::table('order_dishes')
            //     ->whereDate('created_at', $today)
            //     ->sum('quantity');

            if ($todayOrders > 0) {
                $this->updateHistoryFile($today->format('Y-m-d'), $todayOrders);

                Log::info('Demanda diaria registrada automáticamente', [
                    'date' => $today->format('Y-m-d'),
                    'quantity' => $todayOrders
                ]);
            }

            return response()->json([
                'success' => true,
                'recorded' => $todayOrders > 0,
                'quantity' => $todayOrders
            ]);

        } catch (\Exception $e) {
            Log::error('Error en registro automático', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualiza el archivo CSV histórico
     */
    private function updateHistoryFile($date, $quantity)
    {
        // Asegurar que el directorio existe
        $directory = dirname($this->historyFile);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $data = $this->loadHistoryData();

        // Buscar si ya existe el registro para esta fecha
        $found = false;
        foreach ($data as $key => $row) {
            if ($row['date'] === $date) {
                // Acumular demanda del mismo día
                $data[$key]['demand'] = floatval($row['demand']) + floatval($quantity);
                $found = true;
                break;
            }
        }

        // Si no existe, agregar nuevo registro
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

        // Guardar en CSV
        $fp = fopen($this->historyFile, 'w');
        fputcsv($fp, ['date', 'demand']);

        foreach ($data as $row) {
            fputcsv($fp, [$row['date'], $row['demand']]);
        }

        fclose($fp);
    }

    /**
     * Carga datos históricos del CSV
     */
    private function loadHistoryData()
    {
        if (!file_exists($this->historyFile)) {
            return [];
        }

        $data = [];
        $fp = fopen($this->historyFile, 'r');

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

    /**
     * Limpia datos antiguos (opcional)
     */
    public function cleanOldData(Request $request)
    {
        $daysToKeep = $request->input('days', 90);

        try {
            $data = $this->loadHistoryData();
            $cutoffDate = Carbon::now()->subDays($daysToKeep)->format('Y-m-d');

            $filtered = array_filter($data, function($row) use ($cutoffDate) {
                return $row['date'] >= $cutoffDate;
            });

            // Guardar datos filtrados
            $fp = fopen($this->historyFile, 'w');
            fputcsv($fp, ['date', 'demand']);

            foreach ($filtered as $row) {
                fputcsv($fp, [$row['date'], $row['demand']]);
            }

            fclose($fp);

            return response()->json([
                'success' => true,
                'removed' => count($data) - count($filtered),
                'remaining' => count($filtered)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Exportar datos históricos
     */
    public function exportHistory()
    {
        try {
            $data = $this->loadHistoryData();

            return response()->json([
                'data' => $data,
                'total_records' => count($data),
                'date_range' => [
                    'start' => count($data) > 0 ? $data[0]['date'] : null,
                    'end' => count($data) > 0 ? $data[count($data) - 1]['date'] : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
