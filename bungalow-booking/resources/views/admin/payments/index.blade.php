<x-layouts.app title="Manage Payments">
    <div class="section-head">
        <div>
            <h1>Payments</h1>
            <p class="muted">Manual payment tracking for booking requests.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Booking</th><th>Customer</th><th>Amount</th><th>Status</th><th>Reference</th><th></th></tr></thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->booking->bungalow->title }}</td>
                        <td>{{ $payment->booking->user->name }}</td>
                        <td>LKR {{ number_format((float) $payment->amount, 2) }}</td>
                        <td><span class="badge">{{ $payment->status }}</span></td>
                        <td>{{ $payment->transaction_reference }}</td>
                        <td class="actions">
                            @if($payment->status !== 'paid')
                                <form class="actions" method="POST" action="{{ route('admin.payments.paid', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input name="transaction_reference" placeholder="Reference">
                                    <button class="button secondary" type="submit">Mark paid</button>
                                </form>
                            @endif
                            @if($payment->status === 'paid')
                                <form method="POST" action="{{ route('admin.payments.refund', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="button danger" type="submit">Refund</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $payments->links() }}</div>
</x-layouts.app>
