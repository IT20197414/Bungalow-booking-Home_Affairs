<x-layouts.app title="Manage Bookings">
    <div class="section-head">
        <div>
            <h1>Bookings</h1>
            <p class="muted">Review requests and update reservation states.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Customer</th><th>Bungalow</th><th>Dates</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr></thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->bungalow->title }}</td>
                        <td>{{ $booking->check_in_date->toDateString() }} to {{ $booking->check_out_date->toDateString() }}</td>
                        <td>${{ $booking->total_amount }}</td>
                        <td><span class="badge">{{ $booking->status }}</span></td>
                        <td><span class="badge">{{ $booking->payment?->status ?? 'pending' }}</span></td>
                        <td>
                            <form class="actions" method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status">
                                    @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="button secondary" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">No bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $bookings->links() }}</div>
</x-layouts.app>
