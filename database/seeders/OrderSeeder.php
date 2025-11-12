<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ingredient;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Crear órdenes de compra de los últimos 60 días
        $orders = [
            // Hace 60 días - Orden grande de reabastecimiento
            [
                'supplier_id' => 1,
                'date' => Carbon::now()->subDays(60),
                'status' => 'received',
                'items' => [
                    1 => ['quantity' => 25.0, 'unit_cost' => 85.00],  // Pollo
                    2 => ['quantity' => 15.0, 'unit_cost' => 220.00], // Res Premium
                    3 => ['quantity' => 20.0, 'unit_cost' => 130.00], // Carne Molida
                    5 => ['quantity' => 8.0, 'unit_cost' => 180.00],  // Tocino
                ]
            ],
            
            // Hace 55 días - Verduras frescas
            [
                'supplier_id' => 2,
                'date' => Carbon::now()->subDays(55),
                'status' => 'received',
                'items' => [
                    7 => ['quantity' => 30.0, 'unit_cost' => 35.00],  // Tomate
                    8 => ['quantity' => 20.0, 'unit_cost' => 28.00],  // Lechuga
                    11 => ['quantity' => 25.0, 'unit_cost' => 68.00], // Aguacate
                    13 => ['quantity' => 40.0, 'unit_cost' => 20.00], // Papa
                ]
            ],

            // Hace 50 días - Lácteos
            [
                'supplier_id' => 3,
                'date' => Carbon::now()->subDays(50),
                'status' => 'received',
                'items' => [
                    17 => ['quantity' => 10.0, 'unit_cost' => 250.00], // Queso Manchego
                    20 => ['quantity' => 15.0, 'unit_cost' => 60.00],  // Crema
                    21 => ['quantity' => 30.0, 'unit_cost' => 25.00],  // Leche
                ]
            ],

            // Hace 45 días - Mariscos
            [
                'supplier_id' => 4,
                'date' => Carbon::now()->subDays(45),
                'status' => 'received',
                'items' => [
                    23 => ['quantity' => 12.0, 'unit_cost' => 150.00], // Pescado
                    24 => ['quantity' => 10.0, 'unit_cost' => 280.00], // Camarones
                    25 => ['quantity' => 8.0, 'unit_cost' => 320.00],  // Salmón
                ]
            ],

            // Hace 40 días - Abarrotes
            [
                'supplier_id' => 5,
                'date' => Carbon::now()->subDays(40),
                'status' => 'received',
                'items' => [
                    26 => ['quantity' => 50.0, 'unit_cost' => 18.00],  // Arroz
                    27 => ['quantity' => 40.0, 'unit_cost' => 25.00],  // Frijol
                    28 => ['quantity' => 30.0, 'unit_cost' => 32.00],  // Pasta Spaghetti
                    32 => ['quantity' => 50.0, 'unit_cost' => 15.00],  // Harina
                ]
            ],

            // Hace 30 días - Carnes mixtas
            [
                'supplier_id' => 1,
                'date' => Carbon::now()->subDays(30),
                'status' => 'received',
                'items' => [
                    1 => ['quantity' => 20.0, 'unit_cost' => 85.00],
                    4 => ['quantity' => 12.0, 'unit_cost' => 160.00], // Cerdo
                    6 => ['quantity' => 8.0, 'unit_cost' => 95.00],   // Chorizo
                ]
            ],

            // Hace 25 días - Verduras semanales
            [
                'supplier_id' => 2,
                'date' => Carbon::now()->subDays(25),
                'status' => 'received',
                'items' => [
                    7 => ['quantity' => 25.0, 'unit_cost' => 35.00],
                    9 => ['quantity' => 20.0, 'unit_cost' => 22.00],  // Cebolla
                    10 => ['quantity' => 15.0, 'unit_cost' => 45.00], // Pimiento
                    16 => ['quantity' => 10.0, 'unit_cost' => 65.00], // Champiñones
                ]
            ],

            // Hace 20 días - Lácteos y quesos
            [
                'supplier_id' => 3,
                'date' => Carbon::now()->subDays(20),
                'status' => 'received',
                'items' => [
                    17 => ['quantity' => 8.0, 'unit_cost' => 250.00],
                    18 => ['quantity' => 6.0, 'unit_cost' => 110.00], // Queso Panela
                    19 => ['quantity' => 6.0, 'unit_cost' => 140.00], // Queso Oaxaca
                    22 => ['quantity' => 8.0, 'unit_cost' => 180.00], // Mantequilla
                ]
            ],

            // Hace 15 días - Orden mixta grande
            [
                'supplier_id' => 1,
                'date' => Carbon::now()->subDays(15),
                'status' => 'received',
                'items' => [
                    1 => ['quantity' => 18.0, 'unit_cost' => 85.00],
                    2 => ['quantity' => 12.0, 'unit_cost' => 220.00],
                    3 => ['quantity' => 18.0, 'unit_cost' => 130.00],
                ]
            ],

            // Hace 12 días - Verduras y frutas
            [
                'supplier_id' => 2,
                'date' => Carbon::now()->subDays(12),
                'status' => 'received',
                'items' => [
                    11 => ['quantity' => 20.0, 'unit_cost' => 68.00],
                    12 => ['quantity' => 15.0, 'unit_cost' => 18.00], // Zanahoria
                    14 => ['quantity' => 12.0, 'unit_cost' => 42.00], // Brócoli
                    40 => ['quantity' => 5.0, 'unit_cost' => 40.00],  // Cilantro
                ]
            ],

            // Hace 10 días - Pan fresco
            [
                'supplier_id' => 7,
                'date' => Carbon::now()->subDays(10),
                'status' => 'received',
                'items' => [
                    42 => ['quantity' => 100.0, 'unit_cost' => 8.00],  // Pan Hamburguesa
                    43 => ['quantity' => 50.0, 'unit_cost' => 15.00],  // Baguette (si existe ID 43)
                ]
            ],

            // Hace 7 días - Mariscos frescos
            [
                'supplier_id' => 4,
                'date' => Carbon::now()->subDays(7),
                'status' => 'received',
                'items' => [
                    23 => ['quantity' => 10.0, 'unit_cost' => 150.00],
                    24 => ['quantity' => 8.0, 'unit_cost' => 280.00],
                ]
            ],

            // Hace 5 días - Orden de emergencia
            [
                'supplier_id' => 2,
                'date' => Carbon::now()->subDays(5),
                'status' => 'received',
                'items' => [
                    7 => ['quantity' => 20.0, 'unit_cost' => 35.00],
                    8 => ['quantity' => 15.0, 'unit_cost' => 28.00],
                    11 => ['quantity' => 15.0, 'unit_cost' => 68.00],
                ]
            ],

            // Hace 3 días - Carnes
            [
                'supplier_id' => 1,
                'date' => Carbon::now()->subDays(3),
                'status' => 'received',
                'items' => [
                    1 => ['quantity' => 15.0, 'unit_cost' => 85.00],
                    3 => ['quantity' => 15.0, 'unit_cost' => 130.00],
                    5 => ['quantity' => 6.0, 'unit_cost' => 180.00],
                ]
            ],

            // Ayer - Orden pendiente de recibir
            [
                'supplier_id' => 3,
                'date' => Carbon::now()->subDay(),
                'status' => 'pending',
                'items' => [
                    17 => ['quantity' => 10.0, 'unit_cost' => 250.00],
                    20 => ['quantity' => 12.0, 'unit_cost' => 60.00],
                    21 => ['quantity' => 25.0, 'unit_cost' => 25.00],
                ]
            ],

            // Hoy - Orden recién creada
            [
                'supplier_id' => 5,
                'date' => Carbon::now(),
                'status' => 'pending',
                'items' => [
                    26 => ['quantity' => 40.0, 'unit_cost' => 18.00],
                    28 => ['quantity' => 25.0, 'unit_cost' => 32.00],
                    30 => ['quantity' => 10.0, 'unit_cost' => 180.00], // Aceite de Oliva
                ]
            ],
        ];

        $orderCount = 0;
        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            // Calcular total
            $total = 0;
            foreach ($items as $ingredientId => $itemData) {
                $total += $itemData['quantity'] * $itemData['unit_cost'];
            }
            $orderData['total'] = $total;

            $order = Order::create($orderData);

            // Crear items de la orden
            foreach ($items as $ingredientId => $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ingredient_id' => $ingredientId,
                    'quantity' => $itemData['quantity'],
                    'unit_cost' => $itemData['unit_cost'],
                    'subtotal' => $itemData['quantity'] * $itemData['unit_cost']
                ]);
            }

            $orderCount++;
        }

        $this->command->info('✓ Órdenes de compra creadas: ' . $orderCount);
    }
}
