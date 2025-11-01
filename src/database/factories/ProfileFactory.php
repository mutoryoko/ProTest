<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'user_image' => 'profile-images/' . $this->faker->lexify('profile_image_????.jpg'),
            'postcode' => $this->faker->numerify('###-####'),
            'address' => $this->faker->prefecture . $this->faker->city . $this->faker->streetAddress,
            'building' => $this->faker->secondaryAddress,
        ];
    }
}
