<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bungalow;
use App\Models\Payment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'bungalowCount' => Bungalow::count(),
            'bookingCount' => Booking::count(),
            'customerCount' => User::where('role', 'customer')->count(),
            'pendingPaymentCount' => Payment::where('status', 'pending')->count(),
            'latestBookings' => Booking::with('user', 'bungalow')->latest()->take(5)->get(),
        ]);
    }
}
