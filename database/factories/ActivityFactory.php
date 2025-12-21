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
            'user_id' => $this->faker->numberBetween(1, 11),
            'specie_id' => $this->faker->numberBetween(1, 20),
            'size' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->randomFloat(1, 0.5, 15),
            'lure_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
