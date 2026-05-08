<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Bungalow;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->with('bungalow', 'payment')
            ->latest()
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function store(StoreBookingRequest $request, Bungalow $bungalow, BookingService $bookingService, PaymentService $paymentService): RedirectResponse
    {
        $booking = $bookingService->createBooking($request->user(), $bungalow, $request->validated());
        $paymentService->createPendingPayment($booking);

        return redirect()
            ->route('customer.bookings.index')
            ->with('status', 'Booking request created. Admin confirmation is pending.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_if($booking->status === Booking::STATUS_COMPLETED, 422);

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return back()->with('status', 'Booking cancelled.');
    }
}
