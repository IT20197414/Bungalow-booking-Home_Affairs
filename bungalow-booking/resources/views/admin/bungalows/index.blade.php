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
                            <form method="POST" action="{{ route('admin.bungalows.destroy', $bungalow) }}">
                                @csrf
                                @method('DELETE')
                                <button class="button danger js-delete-bungalow" type="button" data-bungalow-title="{{ $bungalow->title }}">Delete</button>
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

    <div class="modal-overlay" id="deleteBungalowModal" aria-hidden="true">
        <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="deleteBungalowModalTitle">
            <div class="modal-header">
                <h2 id="deleteBungalowModalTitle">Delete bungalow?</h2>
            </div>
            <div class="modal-body stack">
                <p>You are about to delete <strong id="deleteBungalowName"></strong>.</p>
                <p class="muted">This action cannot be undone. The bungalow and its uploaded photos will be removed.</p>
            </div>
            <div class="modal-actions">
                <button class="button secondary" type="button" id="cancelDeleteBungalow">Cancel</button>
                <button class="button danger" type="button" id="confirmDeleteBungalow">Delete bungalow</button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('deleteBungalowModal');
            const bungalowName = document.getElementById('deleteBungalowName');
            const cancelButton = document.getElementById('cancelDeleteBungalow');
            const confirmButton = document.getElementById('confirmDeleteBungalow');
            let selectedForm = null;

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                selectedForm = null;
            };

            document.querySelectorAll('.js-delete-bungalow').forEach((button) => {
                button.addEventListener('click', () => {
                    selectedForm = button.closest('form');
                    bungalowName.textContent = button.dataset.bungalowTitle;
                    modal.classList.add('is-open');
                    modal.setAttribute('aria-hidden', 'false');
                    cancelButton.focus();
                });
            });

            cancelButton.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
            confirmButton.addEventListener('click', () => {
                if (selectedForm) {
                    selectedForm.submit();
                }
            });
        })();
    </script>
</x-layouts.app>
