<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('admin.payments.index', [
            'payments' => Payment::with('booking.user', 'booking.bungalow')->latest()->paginate(15),
        ]);
    }

    public function markPaid(Request $request, Payment $payment, PaymentService $paymentService): RedirectResponse
    {
        $data = $request->validate([
            'transaction_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentService->markPaid($payment, $data['transaction_reference'] ?? null);

        return back()->with('status', 'Payment marked as paid.');
    }

    public function refund(Payment $payment, PaymentService $paymentService): RedirectResponse
    {
        $paymentService->markRefunded($payment);

        return back()->with('status', 'Payment marked as refunded.');
    }
}
