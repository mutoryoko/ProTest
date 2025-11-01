<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_name' => $this->faker->word,
            'brand' => $this->faker->word,
            'item_image' => 'item-images/' . $this->faker->lexify('image_?????.jpg'),
            'price' => $this->faker->numberBetween(100, 10000),
            'condition' => $this->faker->numberBetween(1, 4),
            'description' => $this->faker->text(),
        ];
    }
}
