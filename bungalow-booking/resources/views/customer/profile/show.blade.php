<x-layouts.app title="Profile">
    <div class="card">
        <div class="card-body stack">
            <h1>{{ $user->name }}</h1>
            <p class="muted">{{ $user->email }}</p>
            <span class="badge">{{ $user->role }}</span>
        </div>
    </div>
</x-layouts.app>
