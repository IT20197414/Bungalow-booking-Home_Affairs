<?php

namespace Database\Factories;

use App\Models\Bungalow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bungalow>
 */
class BungalowFactory extends Factory
{
    protected $model = Bungalow::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true).' Bungalow',
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'capacity' => fake()->numberBetween(2, 10),
            'bedrooms' => fake()->numberBetween(1, 5),
            'bathrooms' => fake()->numberBetween(1, 4),
            'nightly_rate' => fake()->numberBetween(80, 450),
            'status' => 'available',
            'featured' => fake()->boolean(35),
            'check_in_time' => '14:00',
            'check_out_time' => '11:00',
        ];
    }
}
