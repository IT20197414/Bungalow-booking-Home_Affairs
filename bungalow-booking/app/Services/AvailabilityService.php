<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Bungalow;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class AvailabilityService
{
    public function isAvailable(Bungalow $bungalow, string|CarbonInterface $checkIn, string|CarbonInterface $checkOut, ?int $ignoreBookingId = null): bool
    {
        $checkInDate = Carbon::parse($checkIn)->toDateString();
        $checkOutDate = Carbon::parse($checkOut)->toDateString();

        if ($checkInDate >= $checkOutDate || $bungalow->status !== 'available') {
            return false;
        }

        return ! $bungalow->bookings()
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->when($ignoreBookingId, fn ($query) => $query->whereKeyNot($ignoreBookingId))
            ->where('check_in_date', '<', $checkOutDate)
            ->where('check_out_date', '>', $checkInDate)
            ->exists();
    }
}
