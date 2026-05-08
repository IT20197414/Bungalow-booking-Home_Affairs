<x-layouts.app title="Browse Bungalows">
    <div class="section-head">
        <div>
            <h1>Browse Bungalows</h1>
            <p class="muted">Filter by city or group size.</p>
        </div>
    </div>

    <form class="card card-body form-grid" method="GET" action="{{ route('bungalows.index') }}">
        <label>City
            <input name="city" value="{{ request('city') }}" placeholder="Kandy">
        </label>
        <label>Guests
            <input type="number" min="1" name="guests" value="{{ request('guests') }}">
        </label>
        <div style="align-self:end" class="actions">
            <button class="button" type="submit">Filter</button>
            <a class="button secondary" href="{{ route('bungalows.index') }}">Clear</a>
        </div>
    </form>

    <div style="height:18px"></div>

    <div class="grid cards">
        @forelse($bungalows as $bungalow)
            <article class="card">
                @if($bungalow->primaryImage)
                    <div class="media image">
                        <img src="{{ asset('storage/'.$bungalow->primaryImage->path) }}" alt="{{ $bungalow->title }}">
                    </div>
                @else
                    <div class="media">{{ $bungalow->city ?? 'Bungalow' }}</div>
                @endif
                <div class="card-body stack">
                    <div>
                        <h3>{{ $bungalow->title }}</h3>
                        <p class="muted">{{ $bungalow->capacity }} guests · {{ $bungalow->bedrooms }} bedrooms · {{ $bungalow->bathrooms }} bathrooms</p>
                        <p><strong>${{ $bungalow->nightly_rate }}</strong> / night</p>
                    </div>
                    <div class="actions">
                        @foreach($bungalow->amenities->take(3) as $amenity)
                            <span class="badge">{{ $amenity->name }}</span>
                        @endforeach
                    </div>
                    <a class="button secondary" href="{{ route('bungalows.show', $bungalow) }}">View details</a>
                </div>
            </article>
        @empty
            <div class="card"><div class="card-body">No bungalows match your search.</div></div>
        @endforelse
    </div>

    <div class="pagination">{{ $bungalows->links() }}</div>
</x-layouts.app>
