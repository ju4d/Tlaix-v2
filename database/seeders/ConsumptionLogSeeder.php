<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ConsumptionLog;
use Carbon\Carbon;

class ConsumptionLogSeeder extends Seeder {
    public function run(){
        $today = Carbon::today();
        for($i=10; $i>=1; $i--){
            ConsumptionLog::create([
                'date' => $today->copy()->subDays($i),
                'dish_id' => null,
                'ingredient_id' => null,
                'quantity' => rand(80,150) // ej. comensales/platillos consumidos
            ]);
        }
    }
}
