<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(private readonly AvailabilityService $availabilityService) {}

    public function createBooking(User $user, Bungalow $bungalow, array $data): Booking
    {
        if (! $this->availabilityService->isAvailable($bungalow, $data['check_in_date'], $data['check_out_date'])) {
            throw ValidationException::withMessages([
                'check_in_date' => 'This bungalow is not available for the selected dates.',
            ]);
        }

        $nights = Carbon::parse($data['check_in_date'])->diffInDays(Carbon::parse($data['check_out_date']));

        return $bungalow->bookings()->create([
            'user_id' => $user->id,
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'guests' => $data['guests'],
            'total_amount' => $nights * $bungalow->nightly_rate,
            'status' => Booking::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
