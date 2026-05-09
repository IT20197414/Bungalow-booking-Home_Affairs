<x-layouts.app title="Manage Bungalows">
    <div class="section-head">
        <div>
            <h1>Bungalows</h1>
            <p class="muted">Create and maintain bungalow listings.</p>
        </div>
        <a class="button" href="{{ route('admin.bungalows.create') }}">New bungalow</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Title</th><th>City</th><th>Capacity</th><th>Rate</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($bungalows as $bungalow)
                    <tr>
                        <td>{{ $bungalow->title }}</td>
                        <td>{{ $bungalow->city }}</td>
                        <td>{{ $bungalow->capacity }}</td>
                        <td>LKR {{ number_format((float) $bungalow->nightly_rate, 2) }}</td>
                        <td><span class="badge">{{ $bungalow->status }}</span></td>
                        <td class="actions">
                            <a class="button secondary" href="{{ route('admin.bungalows.edit', $bungalow) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.bungalows.destroy', $bungalow) }}" onsubmit="return confirm('Delete {{ addslashes($bungalow->title) }}? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="button danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No bungalows yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $bungalows->links() }}</div>
</x-layouts.app>
