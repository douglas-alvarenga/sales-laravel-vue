<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $amount = fake()->randomFloat(2, 10, 9999);
        $sellerCommissionPercentage = env('SELLER_COMMISSION_PERCENTAGE', 8.5);
        return [
            'amount' => $amount,
            'seller_commission_amount' => $amount * ($sellerCommissionPercentage / 100),
            'seller_commission_percentage' => $sellerCommissionPercentage,
            'date' => fake()->dateTimeBetween('-1 month')->format('Y-m-d H:i:s'),
            'seller_id' => fn() => Seller::factory()->create()->id
        ];
    }
}
