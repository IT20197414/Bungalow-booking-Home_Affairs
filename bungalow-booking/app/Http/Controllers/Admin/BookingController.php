<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('admin.bookings.index', [
            'bookings' => Booking::with('user', 'bungalow', 'payment')->latest()->paginate(15),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_COMPLETED,
            ])],
        ]);

        $booking->update($data);

        return back()->with('status', 'Booking status updated.');
    }
}
