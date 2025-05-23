<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Seed the application's database with sellers.
     *
     * @return void
     */
    public function run()
    {
        $count = env('SELLER_SEEDER_COUNT', 10);
        Seller::factory($count)->create();
    }
}
