<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    public function createPendingPayment(Booking $booking, string $method = 'manual'): Payment
    {
        return $booking->payment()->create([
            'amount' => $booking->total_amount,
            'method' => $method,
            'status' => Payment::STATUS_PENDING,
        ]);
    }

    public function markPaid(Payment $payment, ?string $reference = null): Payment
    {
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'transaction_reference' => $reference ?? $payment->transaction_reference,
            'paid_at' => now(),
        ]);

        $payment->booking()->update([
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        return $payment->refresh();
    }

    public function markRefunded(Payment $payment): Payment
    {
        $payment->update(['status' => Payment::STATUS_REFUNDED]);

        return $payment->refresh();
    }
}
