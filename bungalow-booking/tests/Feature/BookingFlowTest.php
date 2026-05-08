<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_booking_for_available_dates(): void
    {
        $user = User::factory()->customer()->create();
        $bungalow = Bungalow::factory()->create([
            'capacity' => 4,
            'nightly_rate' => 100,
        ]);

        $response = $this->actingAs($user)->post(route('customer.bookings.store', $bungalow), [
            'check_in_date' => now()->addDays(10)->toDateString(),
            'check_out_date' => now()->addDays(13)->toDateString(),
            'guests' => 2,
        ]);

        $response->assertRedirect(route('customer.bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'bungalow_id' => $bungalow->id,
            'total_amount' => 300,
            'status' => Booking::STATUS_PENDING,
        ]);
        $this->assertDatabaseHas('payments', [
            'amount' => 300,
            'status' => 'pending',
        ]);
    }

    public function test_booking_is_rejected_when_dates_overlap_existing_confirmed_booking(): void
    {
        $user = User::factory()->customer()->create();
        $bungalow = Bungalow::factory()->create(['capacity' => 4]);

        Booking::factory()->confirmed()->create([
            'bungalow_id' => $bungalow->id,
            'check_in_date' => now()->addDays(10)->toDateString(),
            'check_out_date' => now()->addDays(14)->toDateString(),
        ]);

        $response = $this->actingAs($user)->post(route('customer.bookings.store', $bungalow), [
            'check_in_date' => now()->addDays(12)->toDateString(),
            'check_out_date' => now()->addDays(15)->toDateString(),
            'guests' => 2,
        ]);

        $response->assertSessionHasErrors('check_in_date');
    }
}
