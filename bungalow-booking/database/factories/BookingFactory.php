<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('+1 week', '+2 months');
        $checkOut = (clone $checkIn)->modify('+'.fake()->numberBetween(1, 5).' days');

        return [
            'user_id' => User::factory()->customer(),
            'bungalow_id' => Bungalow::factory(),
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'guests' => fake()->numberBetween(1, 4),
            'total_amount' => fake()->numberBetween(120, 1200),
            'status' => Booking::STATUS_PENDING,
            'notes' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }
}
