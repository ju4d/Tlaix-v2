<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;
use Illuminate\Support\Facades\DB;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $dishes = [
            // ENTRADAS
            [
                'name' => 'Ensalada César',
                'description' => 'Lechuga romana, pollo a la parrilla, queso parmesano, crutones y aderezo césar',
                'price' => 145.00,
                'available' => true,
                'ingredients' => [
                    8 => 0.15,   // Lechuga Romana
                    1 => 0.12,   // Pechuga de Pollo
                    17 => 0.05,  // Queso Manchego
                    41 => 0.02,  // Limón
                ]
            ],
            [
                'name' => 'Ensalada Mixta',
                'description' => 'Lechuga, tomate, zanahoria, pepino con vinagreta',
                'price' => 95.00,
                'available' => true,
                'ingredients' => [
                    8 => 0.10,   // Lechuga Romana
                    7 => 0.08,   // Tomate
                    12 => 0.05,  // Zanahoria
                    41 => 0.02,  // Limón
                ]
            ],
            [
                'name' => 'Guacamole con Totopos',
                'description' => 'Aguacate fresco con pico de gallo y totopos crujientes',
                'price' => 85.00,
                'available' => true,
                'ingredients' => [
                    11 => 0.20,  // Aguacate
                    7 => 0.05,   // Tomate
                    9 => 0.03,   // Cebolla
                    40 => 0.01,  // Cilantro
                    41 => 0.02,  // Limón
                ]
            ],

            // SOPAS
            [
                'name' => 'Sopa de Tortilla',
                'description' => 'Caldo de tomate con tiras de tortilla, aguacate, queso y crema',
                'price' => 75.00,
                'available' => true,
                'ingredients' => [
                    7 => 0.15,   // Tomate
                    11 => 0.05,  // Aguacate
                    17 => 0.03,  // Queso
                    20 => 0.02,  // Crema
                ]
            ],

            // PLATOS PRINCIPALES - POLLO
            [
                'name' => 'Pollo a la Parrilla',
                'description' => 'Pechuga de pollo marinada con vegetales asados y papas',
                'price' => 195.00,
                'available' => true,
                'ingredients' => [
                    1 => 0.25,   // Pechuga de Pollo
                    13 => 0.15,  // Papa
                    10 => 0.08,  // Pimiento
                    7 => 0.05,   // Tomate
                ]
            ],
            [
                'name' => 'Pollo en Salsa de Champiñones',
                'description' => 'Pechuga de pollo en cremosa salsa de champiñones con arroz',
                'price' => 215.00,
                'available' => true,
                'ingredients' => [
                    1 => 0.25,   // Pechuga de Pollo
                    16 => 0.12,  // Champiñones
                    20 => 0.08,  // Crema
                    26 => 0.15,  // Arroz
                ]
            ],

            // PLATOS PRINCIPALES - CARNE
            [
                'name' => 'Filete de Res Premium',
                'description' => 'Corte premium de res con puré de papa y espárragos',
                'price' => 385.00,
                'available' => true,
                'ingredients' => [
                    2 => 0.30,   // Carne de Res Premium
                    13 => 0.20,  // Papa
                    22 => 0.05,  // Mantequilla
                ]
            ],
            [
                'name' => 'Hamburguesa Tlaix',
                'description' => 'Carne angus 200g, queso, tocino, aguacate y papas fritas',
                'price' => 175.00,
                'available' => true,
                'ingredients' => [
                    3 => 0.20,   // Carne Molida
                    5 => 0.05,   // Tocino
                    11 => 0.08,  // Aguacate
                    17 => 0.05,  // Queso Manchego
                    13 => 0.25,  // Papa
                ]
            ],
            [
                'name' => 'Tacos de Carne Asada',
                'description' => 'Tres tacos de carne asada con guacamole y frijoles',
                'price' => 155.00,
                'available' => true,
                'ingredients' => [
                    2 => 0.18,   // Carne de Res
                    11 => 0.10,  // Aguacate
                    9 => 0.05,   // Cebolla
                    40 => 0.02,  // Cilantro
                    27 => 0.15,  // Frijol
                ]
            ],

            // PLATOS PRINCIPALES - CERDO
            [
                'name' => 'Lomo de Cerdo en Salsa BBQ',
                'description' => 'Lomo de cerdo glaseado con puré de camote',
                'price' => 225.00,
                'available' => true,
                'ingredients' => [
                    4 => 0.25,   // Lomo de Cerdo
                    13 => 0.20,  // Papa
                ]
            ],

            // PLATOS PRINCIPALES - MARISCOS
            [
                'name' => 'Pescado a la Veracruzana',
                'description' => 'Filete de pescado en salsa de tomate con aceitunas y alcaparras',
                'price' => 245.00,
                'available' => true,
                'ingredients' => [
                    23 => 0.25,  // Pescado Blanco
                    7 => 0.15,   // Tomate
                    9 => 0.05,   // Cebolla
                    26 => 0.15,  // Arroz
                ]
            ],
            [
                'name' => 'Camarones al Ajillo',
                'description' => 'Camarones salteados con ajo, chile y aceite de oliva',
                'price' => 285.00,
                'available' => true,
                'ingredients' => [
                    24 => 0.22,  // Camarones
                    30 => 0.05,  // Aceite de Oliva
                    28 => 0.15,  // Pasta Spaghetti
                ]
            ],
            [
                'name' => 'Salmón a la Parrilla',
                'description' => 'Filete de salmón con vegetales al vapor',
                'price' => 325.00,
                'available' => true,
                'ingredients' => [
                    25 => 0.25,  // Salmón
                    14 => 0.10,  // Brócoli
                    12 => 0.08,  // Zanahoria
                ]
            ],

            // PASTAS
            [
                'name' => 'Pasta Alfredo',
                'description' => 'Pasta fettuccine en salsa cremosa de queso con pollo',
                'price' => 185.00,
                'available' => true,
                'ingredients' => [
                    28 => 0.15,  // Pasta
                    20 => 0.12,  // Crema
                    17 => 0.08,  // Queso Manchego
                    1 => 0.15,   // Pollo
                ]
            ],
            [
                'name' => 'Pasta Boloñesa',
                'description' => 'Spaghetti con salsa de carne molida y especias',
                'price' => 165.00,
                'available' => true,
                'ingredients' => [
                    28 => 0.15,  // Pasta Spaghetti
                    3 => 0.18,   // Carne Molida
                    7 => 0.12,   // Tomate
                    9 => 0.05,   // Cebolla
                    17 => 0.05,  // Queso
                ]
            ],
            [
                'name' => 'Pasta Primavera',
                'description' => 'Penne con vegetales frescos en salsa ligera',
                'price' => 155.00,
                'available' => true,
                'ingredients' => [
                    29 => 0.15,  // Pasta Penne
                    14 => 0.10,  // Brócoli
                    10 => 0.08,  // Pimiento
                    12 => 0.08,  // Zanahoria
                    7 => 0.08,   // Tomate
                ]
            ],

            // POSTRES
            [
                'name' => 'Flan Casero',
                'description' => 'Flan de vainilla con caramelo',
                'price' => 65.00,
                'available' => true,
                'ingredients' => [
                    21 => 0.15,  // Leche
                    33 => 0.08,  // Azúcar
                ]
            ],
            [
                'name' => 'Pay de Queso',
                'description' => 'Cheesecake cremoso con base de galleta',
                'price' => 75.00,
                'available' => true,
                'ingredients' => [
                    17 => 0.12,  // Queso
                    20 => 0.08,  // Crema
                    33 => 0.05,  // Azúcar
                ]
            ],
        ];

        foreach ($dishes as $dishData) {
            $ingredients = $dishData['ingredients'];
            unset($dishData['ingredients']);

            $dish = Dish::create($dishData);

            // Attach ingredients with quantities
            foreach ($ingredients as $ingredientId => $quantity) {
                DB::table('dish_ingredient')->insert([
                    'dish_id' => $dish->id,
                    'ingredient_id' => $ingredientId,
                    'quantity_required' => $quantity
                ]);
            }
        }

        $this->command->info('✓ Platillos creados: ' . count($dishes));
    }
}
