<x-layouts.app title="My Bookings">
    <div class="section-head">
        <div>
            <h1>My Bookings</h1>
            <p class="muted">Track requested, confirmed, and completed stays.</p>
        </div>
        <a class="button secondary" href="{{ route('bungalows.index') }}">Book another stay</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Bungalow</th><th>Dates</th><th>Guests</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    @php
                        $checkInTime = $booking->bungalow->check_in_time
                            ? \Illuminate\Support\Carbon::parse($booking->bungalow->check_in_time)->format('g:i A')
                            : 'To be confirmed';
                        $checkOutTime = $booking->bungalow->check_out_time
                            ? \Illuminate\Support\Carbon::parse($booking->bungalow->check_out_time)->format('g:i A')
                            : 'To be confirmed';
                    @endphp
                    <tr>
                        <td>{{ $booking->bungalow->title }}</td>
                        <td>
                            {{ $booking->check_in_date->toFormattedDateString() }} to {{ $booking->check_out_date->toFormattedDateString() }}
                            <div class="muted" style="margin-top:6px">
                                Check-in: From {{ $checkInTime }}<br>
                                Check-out: Before {{ $checkOutTime }}
                            </div>
                        </td>
                        <td>{{ $booking->guests }}</td>
                        <td>LKR {{ number_format((float) $booking->total_amount, 2) }}</td>
                        <td><span class="badge">{{ $booking->status }}</span></td>
                        <td><span class="badge">{{ $booking->payment?->status ?? 'pending' }}</span></td>
                        <td>
                            @if(! in_array($booking->status, ['cancelled', 'completed']))
                                <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="button danger" type="submit">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">You have no bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $bookings->links() }}</div>
</x-layouts.app>
