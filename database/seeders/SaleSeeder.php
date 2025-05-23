<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Seed the application's database with sales.
     *
     * @return void
     */
    public function run()
    {
        $count = env('SALE_SEEDER_COUNT', 10);
        Sale::factory($count)->create();
    }
}
