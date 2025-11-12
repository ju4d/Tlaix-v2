<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            IngredientSeeder::class,
            DishSeeder::class,
            OrderSeeder::class,
            CustomerOrderSeeder::class,
            ConsumptionLogSeeder::class,
            WasteRecordSeeder::class,
        ]);

        $this->command->info('✅ Base de datos poblada exitosamente!');
        $this->command->newLine();
        $this->command->info('👤 Credenciales de acceso:');
        $this->command->info('   Admin:   admin@tlaix.com / admin123');
        $this->command->info('   Chef:    chef@tlaix.com / chef123');
        $this->command->info('   Mesero:  mesero1@tlaix.com / mesero123');
        $this->command->info('   Gerente: gerente@tlaix.com / gerente123');
        $this->command->newLine();
    }
}
