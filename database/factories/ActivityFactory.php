<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 2),
            'specie' => $this->faker->randomElement(['Brochet', 'Truite', 'Ombre']),
            'size' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->randomFloat(1, 0.5, 15),
            'lure' => $this->faker->randomElement(['Cuillère', 'Vif', 'Twist']),
        ];
    }
}
