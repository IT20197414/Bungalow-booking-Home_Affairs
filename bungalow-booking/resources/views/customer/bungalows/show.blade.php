<x-layouts.app :title="$bungalow->title">
    @php
        $checkInTime = $bungalow->check_in_time
            ? \Illuminate\Support\Carbon::parse($bungalow->check_in_time)->format('g:i A')
            : 'To be confirmed';
        $checkOutTime = $bungalow->check_out_time
            ? \Illuminate\Support\Carbon::parse($bungalow->check_out_time)->format('g:i A')
            : 'To be confirmed';
    @endphp

    <div class="grid" style="grid-template-columns: minmax(0, 1.3fr) minmax(280px, .7fr); align-items:start">
        <section class="stack">
            <div class="media" style="min-height:320px">{{ $bungalow->city ?? 'Bungalow' }}</div>
            <div>
                <h1>{{ $bungalow->title }}</h1>
                <p class="muted">{{ $bungalow->address }} {{ $bungalow->city }}</p>
            </div>
            <p>{{ $bungalow->description }}</p>
            <div class="actions">
                <span class="badge">{{ $bungalow->capacity }} guests</span>
                <span class="badge">{{ $bungalow->bedrooms }} bedrooms</span>
                <span class="badge">{{ $bungalow->bathrooms }} bathrooms</span>
                @foreach($bungalow->amenities as $amenity)
                    <span class="badge">{{ $amenity->name }}</span>
                @endforeach
            </div>
        </section>

        <aside class="card">
            <div class="card-body stack">
                <h2>${{ $bungalow->nightly_rate }} / night</h2>
                <div class="stack" style="gap:8px">
                    <strong>Stay policy</strong>
                    <p class="muted" style="margin:0">Check-in: From {{ $checkInTime }}</p>
                    <p class="muted" style="margin:0">Check-out: Before {{ $checkOutTime }}</p>
                </div>
                @auth
                    <form class="stack" method="POST" action="{{ route('customer.bookings.store', $bungalow) }}">
                        @csrf
                        <label>Check in
                            <input type="date" name="check_in_date" value="{{ old('check_in_date') }}" required>
                        </label>
                        <label>Check out
                            <input type="date" name="check_out_date" value="{{ old('check_out_date') }}" required>
                        </label>
                        <label>Guests
                            <input type="number" name="guests" min="1" max="{{ $bungalow->capacity }}" value="{{ old('guests', 1) }}" required>
                        </label>
                        <label>Notes
                            <textarea name="notes">{{ old('notes') }}</textarea>
                        </label>
                        <button class="button" type="submit">Request booking</button>
                    </form>
                @else
                    <a class="button" href="{{ route('login') }}">Login to book</a>
                @endauth
            </div>
        </aside>
    </div>
</x-layouts.app>
