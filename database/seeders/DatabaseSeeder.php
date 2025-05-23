<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        $this->createSales();
    }

    private function createAdmin()
    {
        $user = User::where(['username' => 'admin'])->first();

        if (!$user) {
            $user = User::create(
                [
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'admin@teste.com',
                    'password' => '1234',
                    'is_admin' => true,
                ]
            );
        }
    }

    private function createSales()
    {
        $countSeller = env('SELLER_SEEDER_COUNT', 10);
        Seller::factory($countSeller)->create()->each(function ($seller) {
            $countSales = rand(0, env('SALE_SEEDER_COUNT', 10));
            Sale::factory($countSales)->create(['seller_id' => $seller->id]);
        });
    }
}
