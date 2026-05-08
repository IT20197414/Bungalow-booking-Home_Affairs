<x-layouts.app title="Bungalow Booking">
    <x-slot:hero>
        <h1>Find a quiet stay that fits the trip.</h1>
        <p>Browse curated bungalows, check availability, and send a booking request with a simple customer account.</p>
        <p style="margin-top:18px"><a class="button" href="{{ route('bungalows.index') }}">Browse bungalows</a></p>
    </x-slot:hero>

    <div class="section-head">
        <div>
            <h2>Featured Bungalows</h2>
            <p class="muted">Available stays selected by the admin team.</p>
        </div>
        <a class="button secondary" href="{{ route('bungalows.index') }}">View all</a>
    </div>

    <div class="grid cards">
        @forelse($featuredBungalows as $bungalow)
            <article class="card">
                <div class="media">{{ $bungalow->city ?? 'Stay' }}</div>
                <div class="card-body stack">
                    <div>
                        <h3>{{ $bungalow->title }}</h3>
                        <p class="muted">{{ $bungalow->capacity }} guests · {{ $bungalow->bedrooms }} bedrooms · ${{ $bungalow->nightly_rate }}/night</p>
                    </div>
                    <a class="button secondary" href="{{ route('bungalows.show', $bungalow) }}">View details</a>
                </div>
            </article>
        @empty
            <div class="card"><div class="card-body">No featured bungalows yet.</div></div>
        @endforelse
    </div>
</x-layouts.app>
