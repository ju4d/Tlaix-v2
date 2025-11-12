<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDish;
use Carbon\Carbon;

class CustomerOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Platos más populares por categoría
        $entradas = [1, 2, 3]; // Ensaladas, Guacamole
        $principal = [5, 6, 7, 8, 9, 14, 15]; // Pollo, Carnes, Pastas
        $mariscos = [11, 12, 13]; // Pescado, Camarones, Salmón
        $postres = [17, 18]; // Flan, Pay

        $meseros = [3, 4, 5]; // IDs de meseros

        // Generar órdenes para los últimos 90 días (alineado con history.csv)
        $startDate = Carbon::create(2025, 8, 14);
        $endDate = Carbon::create(2025, 11, 11);
        
        $orderCount = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $isWeekend = $currentDate->isWeekend();
            
            // Determinar número de órdenes según el día
            if ($isWeekend) {
                $numOrders = rand(30, 45); // Más órdenes en fin de semana
            } else {
                $numOrders = rand(18, 32); // Días normales
            }

            // Distribuir órdenes a lo largo del día
            for ($i = 0; $i < $numOrders; $i++) {
                $orderTime = $currentDate->copy();
                
                // Horarios de servicio: 13:00-16:00 (comida) y 19:00-23:00 (cena)
                if (rand(0, 1)) {
                    // Comida
                    $orderTime->setHour(rand(13, 16))->setMinute(rand(0, 59));
                } else {
                    // Cena
                    $orderTime->setHour(rand(19, 23))->setMinute(rand(0, 59));
                }

                $order = CustomerOrder::create([
                    'user_id' => $meseros[array_rand($meseros)],
                    'status' => 'completed',
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime->copy()->addMinutes(rand(20, 45))
                ]);

                // Número de platillos por orden (1-4)
                $numDishes = rand(1, 4);
                
                for ($d = 0; $d < $numDishes; $d++) {
                    // Probabilidades de tipo de platillo
                    $random = rand(1, 100);
                    
                    if ($random <= 15) {
                        // 15% entrada
                        $dishId = $entradas[array_rand($entradas)];
                    } elseif ($random <= 75) {
                        // 60% plato principal
                        $dishId = $principal[array_rand($principal)];
                    } elseif ($random <= 85) {
                        // 10% mariscos
                        $dishId = $mariscos[array_rand($mariscos)];
                    } else {
                        // 15% postre
                        $dishId = $postres[array_rand($postres)];
                    }

                    CustomerOrderDish::create([
                        'customer_order_id' => $order->id,
                        'dish_id' => $dishId,
                        'quantity' => rand(1, 2), // Generalmente 1, a veces 2
                        'completed' => true,
                        'received' => true,
                        'created_at' => $orderTime,
                        'updated_at' => $orderTime->copy()->addMinutes(rand(10, 30))
                    ]);
                }

                $orderCount++;
            }

            $currentDate->addDay();
        }

        $this->command->info('✓ Órdenes de clientes creadas: ' . $orderCount);
    }
}
