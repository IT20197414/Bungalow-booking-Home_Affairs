<x-layouts.app title="Admin Dashboard">
    <div class="section-head">
        <div>
            <h1>Admin Dashboard</h1>
            <p class="muted">Manage bungalows, bookings, and manual payments.</p>
        </div>
        <div class="actions">
            <a class="button secondary" href="{{ route('admin.bungalows.index') }}">Bungalows</a>
            <a class="button secondary" href="{{ route('admin.bookings.index') }}">Bookings</a>
            <a class="button secondary" href="{{ route('admin.payments.index') }}">Payments</a>
        </div>
    </div>

    <div class="grid cards">
        <div class="card"><div class="card-body"><p class="muted">Bungalows</p><h2>{{ $bungalowCount }}</h2></div></div>
        <div class="card"><div class="card-body"><p class="muted">Bookings</p><h2>{{ $bookingCount }}</h2></div></div>
        <div class="card"><div class="card-body"><p class="muted">Customers</p><h2>{{ $customerCount }}</h2></div></div>
        <div class="card"><div class="card-body"><p class="muted">Pending Payments</p><h2>{{ $pendingPaymentCount }}</h2></div></div>
    </div>

    <div style="height:22px"></div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Customer</th><th>Bungalow</th><th>Dates</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($latestBookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->bungalow->title }}</td>
                        <td>{{ $booking->check_in_date->toDateString() }} to {{ $booking->check_out_date->toDateString() }}</td>
                        <td><span class="badge">{{ $booking->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4">No bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
