<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsumptionLog;
use Carbon\Carbon;

class ConsumptionLogSeeder extends Seeder
{
    public function run(): void
    {
        // Datos sincronizados con history.csv (demanda diaria)
        // Del 14 de agosto al 11 de noviembre de 2025
        $consumptionData = [
            ['date' => '2025-08-14', 'quantity' => 27.8],
            ['date' => '2025-08-15', 'quantity' => 31.2],
            ['date' => '2025-08-16', 'quantity' => 35.5],
            ['date' => '2025-08-17', 'quantity' => 33.9],
            ['date' => '2025-08-18', 'quantity' => 22.8],
            ['date' => '2025-08-19', 'quantity' => 24.3],
            ['date' => '2025-08-20', 'quantity' => 23.5],
            ['date' => '2025-08-21', 'quantity' => 29.7],
            ['date' => '2025-08-22', 'quantity' => 38.2],
            ['date' => '2025-08-23', 'quantity' => 44.5],
            ['date' => '2025-08-24', 'quantity' => 42.8],
            ['date' => '2025-08-25', 'quantity' => 28.6],
            ['date' => '2025-08-26', 'quantity' => 26.9],
            ['date' => '2025-08-27', 'quantity' => 24.8],
            ['date' => '2025-08-28', 'quantity' => 30.1],
            ['date' => '2025-08-29', 'quantity' => 33.4],
            ['date' => '2025-08-30', 'quantity' => 36.2],
            ['date' => '2025-08-31', 'quantity' => 34.7],
            ['date' => '2025-09-01', 'quantity' => 18.9],
            ['date' => '2025-09-02', 'quantity' => 19.7],
            ['date' => '2025-09-03', 'quantity' => 21.3],
            ['date' => '2025-09-04', 'quantity' => 28.8],
            ['date' => '2025-09-05', 'quantity' => 31.2],
            ['date' => '2025-09-06', 'quantity' => 35.9],
            ['date' => '2025-09-07', 'quantity' => 38.3],
            ['date' => '2025-09-08', 'quantity' => 26.6],
            ['date' => '2025-09-09', 'quantity' => 27.1],
            ['date' => '2025-09-10', 'quantity' => 23.5],
            ['date' => '2025-09-11', 'quantity' => 27.9],
            ['date' => '2025-09-12', 'quantity' => 29.3],
            ['date' => '2025-09-13', 'quantity' => 32.4],
            ['date' => '2025-09-14', 'quantity' => 30.5],
            ['date' => '2025-09-15', 'quantity' => 19.7],
            ['date' => '2025-09-16', 'quantity' => 18.2],
            ['date' => '2025-09-17', 'quantity' => 20.4],
            ['date' => '2025-09-18', 'quantity' => 25.3],
            ['date' => '2025-09-19', 'quantity' => 30.6],
            ['date' => '2025-09-20', 'quantity' => 37.5],
            ['date' => '2025-09-21', 'quantity' => 39.3],
            ['date' => '2025-09-22', 'quantity' => 26.8],
            ['date' => '2025-09-23', 'quantity' => 28.2],
            ['date' => '2025-09-24', 'quantity' => 25.2],
            ['date' => '2025-09-25', 'quantity' => 24.3],
            ['date' => '2025-09-26', 'quantity' => 28.2],
            ['date' => '2025-09-27', 'quantity' => 31.7],
            ['date' => '2025-09-28', 'quantity' => 29.7],
            ['date' => '2025-09-29', 'quantity' => 17.8],
            ['date' => '2025-09-30', 'quantity' => 19.2],
            ['date' => '2025-10-01', 'quantity' => 18.5],
            ['date' => '2025-10-02', 'quantity' => 27.1],
            ['date' => '2025-10-03', 'quantity' => 29.4],
            ['date' => '2025-10-04', 'quantity' => 33.3],
            ['date' => '2025-10-05', 'quantity' => 35.7],
            ['date' => '2025-10-06', 'quantity' => 23.4],
            ['date' => '2025-10-07', 'quantity' => 25.7],
            ['date' => '2025-10-08', 'quantity' => 24.1],
            ['date' => '2025-10-09', 'quantity' => 26.4],
            ['date' => '2025-10-10', 'quantity' => 28.3],
            ['date' => '2025-10-11', 'quantity' => 30.8],
            ['date' => '2025-10-12', 'quantity' => 32.5],
            ['date' => '2025-10-13', 'quantity' => 19.4],
            ['date' => '2025-10-14', 'quantity' => 20.8],
            ['date' => '2025-10-15', 'quantity' => 22.6],
            ['date' => '2025-10-16', 'quantity' => 26.4],
            ['date' => '2025-10-17', 'quantity' => 32.6],
            ['date' => '2025-10-18', 'quantity' => 36.1],
            ['date' => '2025-10-19', 'quantity' => 34.3],
            ['date' => '2025-10-20', 'quantity' => 26.8],
            ['date' => '2025-10-21', 'quantity' => 24.6],
            ['date' => '2025-10-22', 'quantity' => 23.3],
            ['date' => '2025-10-23', 'quantity' => 25.9],
            ['date' => '2025-10-24', 'quantity' => 28.8],
            ['date' => '2025-10-25', 'quantity' => 31.3],
            ['date' => '2025-10-26', 'quantity' => 29.3],
            ['date' => '2025-10-27', 'quantity' => 18.2],
            ['date' => '2025-10-28', 'quantity' => 19.5],
            ['date' => '2025-10-29', 'quantity' => 20.3],
            ['date' => '2025-10-30', 'quantity' => 28.1],
            ['date' => '2025-10-31', 'quantity' => 31.7],
            ['date' => '2025-11-01', 'quantity' => 34.8],
            ['date' => '2025-11-02', 'quantity' => 37.7],
            ['date' => '2025-11-03', 'quantity' => 26.5],
            ['date' => '2025-11-04', 'quantity' => 27.3],
            ['date' => '2025-11-05', 'quantity' => 25.8],
            ['date' => '2025-11-06', 'quantity' => 27.5],
            ['date' => '2025-11-07', 'quantity' => 29.7],
            ['date' => '2025-11-08', 'quantity' => 32.9],
            ['date' => '2025-11-09', 'quantity' => 30.7],
            ['date' => '2025-11-10', 'quantity' => 21.3],
            ['date' => '2025-11-11', 'quantity' => 22.5],
        ];

        foreach ($consumptionData as $data) {
            ConsumptionLog::create([
                'date' => Carbon::parse($data['date']),
                'dish_id' => null,
                'ingredient_id' => null,
                'quantity' => $data['quantity']
            ]);
        }

        $this->command->info('✓ Logs de consumo creados: ' . count($consumptionData) . ' días de historial');
    }
}
