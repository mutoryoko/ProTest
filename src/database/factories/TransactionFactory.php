<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'buyer_id' => User::factory(),
            'payment_method' => 1, // コンビニ支払い
            'shipping_postcode' => $this->faker->numerify('###-####'),
            'shipping_address' => $this->faker->prefecture . $this->faker->city . $this->faker->streetAddress,
        ];
    }
}
