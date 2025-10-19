<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot>
 */
class SpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'latitude' => 50.61153,
            'longitude' => 5.51042,
            'name' => 'Meuse',
            'license' => 'Les amis de la Mehaigne',
            'url' => 'https://www.lesamisdelamehaigne.be',
            'environement' => ['Rocher', 'Sable'],
            'rules' => ['No kill', 'Pêche de nuit'],
            'is_public' => true,
            'equipments' => ['Parking'],
        ];
    }
}
