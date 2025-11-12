<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WasteRecord;
use App\Models\Ingredient;
use Carbon\Carbon;

class WasteRecordSeeder extends Seeder
{
    public function run(): void
    {
        $wasteRecords = [
            // PRODUCTOS EXPIRADOS
            [
                'ingredient_id' => 8, // Lechuga
                'quantity' => 2.5,
                'reason' => 'expired',
                'comments' => 'Lechuga marchita por exceso de tiempo en refrigeración',
                'unit_cost_at_time' => 28.00,
                'created_at' => Carbon::now()->subDays(45)
            ],
            [
                'ingredient_id' => 7, // Tomate
                'quantity' => 3.0,
                'reason' => 'expired',
                'comments' => 'Tomates con moho, almacenados más de 7 días',
                'unit_cost_at_time' => 35.00,
                'created_at' => Carbon::now()->subDays(38)
            ],
            [
                'ingredient_id' => 23, // Pescado
                'quantity' => 1.5,
                'reason' => 'expired',
                'comments' => 'Pescado fuera del tiempo de consumo seguro',
                'unit_cost_at_time' => 150.00,
                'created_at' => Carbon::now()->subDays(30)
            ],
            [
                'ingredient_id' => 21, // Leche
                'quantity' => 4.0,
                'reason' => 'expired',
                'comments' => 'Leche caducada',
                'unit_cost_at_time' => 25.00,
                'created_at' => Carbon::now()->subDays(25)
            ],

            // DAÑOS EN ALMACENAMIENTO
            [
                'ingredient_id' => 11, // Aguacate
                'quantity' => 1.8,
                'reason' => 'damaged_in_storage',
                'comments' => 'Aguacates golpeados durante almacenamiento',
                'unit_cost_at_time' => 68.00,
                'created_at' => Carbon::now()->subDays(40)
            ],
            [
                'ingredient_id' => 13, // Papa
                'quantity' => 5.0,
                'reason' => 'damaged_in_storage',
                'comments' => 'Papas con brotes y manchas negras',
                'unit_cost_at_time' => 20.00,
                'created_at' => Carbon::now()->subDays(35)
            ],
            [
                'ingredient_id' => 16, // Champiñones
                'quantity' => 0.8,
                'reason' => 'damaged_in_storage',
                'comments' => 'Champiñones con textura babosa',
                'unit_cost_at_time' => 65.00,
                'created_at' => Carbon::now()->subDays(20)
            ],
            [
                'ingredient_id' => 42, // Pan Hamburguesa
                'quantity' => 15.0,
                'reason' => 'damaged_in_storage',
                'comments' => 'Pan enmohecido por humedad en almacén',
                'unit_cost_at_time' => 8.00,
                'created_at' => Carbon::now()->subDays(15)
            ],

            // DEVOLUCIONES DE CLIENTES
            [
                'ingredient_id' => 1, // Pollo
                'quantity' => 0.5,
                'reason' => 'customer_return',
                'comments' => 'Platillo devuelto por cliente - pollo no en el punto solicitado',
                'unit_cost_at_time' => 85.00,
                'created_at' => Carbon::now()->subDays(28)
            ],
            [
                'ingredient_id' => 24, // Camarones
                'quantity' => 0.3,
                'reason' => 'customer_return',
                'comments' => 'Devolución por alergia del cliente no mencionada',
                'unit_cost_at_time' => 280.00,
                'created_at' => Carbon::now()->subDays(18)
            ],

            // ERRORES DE INVENTARIO
            [
                'ingredient_id' => 26, // Arroz
                'quantity' => 2.0,
                'reason' => 'inventory_error',
                'comments' => 'Diferencia detectada en conteo físico vs sistema',
                'unit_cost_at_time' => 18.00,
                'created_at' => Carbon::now()->subDays(50)
            ],
            [
                'ingredient_id' => 30, // Aceite de Oliva
                'quantity' => 0.5,
                'reason' => 'inventory_error',
                'comments' => 'Botella contabilizada pero no localizada',
                'unit_cost_at_time' => 180.00,
                'created_at' => Carbon::now()->subDays(22)
            ],

            // PÉRDIDAS/ROBO
            [
                'ingredient_id' => 2, // Carne de Res
                'quantity' => 1.2,
                'reason' => 'theft_loss',
                'comments' => 'Faltante no justificado en inventario nocturno',
                'unit_cost_at_time' => 220.00,
                'created_at' => Carbon::now()->subDays(42)
            ],

            // USO INTERNO
            [
                'ingredient_id' => 28, // Pasta
                'quantity' => 1.0,
                'reason' => 'internal_use',
                'comments' => 'Usado para pruebas de platillos nuevos del chef',
                'unit_cost_at_time' => 32.00,
                'created_at' => Carbon::now()->subDays(33)
            ],
            [
                'ingredient_id' => 3, // Carne Molida
                'quantity' => 0.8,
                'reason' => 'internal_use',
                'comments' => 'Capacitación de personal de cocina',
                'unit_cost_at_time' => 130.00,
                'created_at' => Carbon::now()->subDays(12)
            ],

            // OTROS
            [
                'ingredient_id' => 14, // Brócoli
                'quantity' => 1.5,
                'reason' => 'other',
                'comments' => 'Derrame accidental durante preparación',
                'unit_cost_at_time' => 42.00,
                'created_at' => Carbon::now()->subDays(17)
            ],
            [
                'ingredient_id' => 20, // Crema
                'quantity' => 0.6,
                'reason' => 'other',
                'comments' => 'Envase roto durante transporte interno',
                'unit_cost_at_time' => 60.00,
                'created_at' => Carbon::now()->subDays(8)
            ],

            // Registros recientes
            [
                'ingredient_id' => 40, // Cilantro
                'quantity' => 0.3,
                'reason' => 'expired',
                'comments' => 'Cilantro seco y amarillento',
                'unit_cost_at_time' => 40.00,
                'created_at' => Carbon::now()->subDays(5)
            ],
            [
                'ingredient_id' => 10, // Pimiento
                'quantity' => 1.2,
                'reason' => 'damaged_in_storage',
                'comments' => 'Pimientos arrugados y blandos',
                'unit_cost_at_time' => 45.00,
                'created_at' => Carbon::now()->subDays(3)
            ],
            [
                'ingredient_id' => 41, // Limón
                'quantity' => 0.8,
                'reason' => 'expired',
                'comments' => 'Limones secos y endurecidos',
                'unit_cost_at_time' => 30.00,
                'created_at' => Carbon::now()->subDay()
            ],
        ];

        foreach ($wasteRecords as $wasteData) {
            WasteRecord::create($wasteData);
        }

        $this->command->info('✓ Registros de desperdicio creados: ' . count($wasteRecords));
    }
}
