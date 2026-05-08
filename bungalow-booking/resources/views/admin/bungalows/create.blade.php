<x-layouts.app title="Create Bungalow">
    <div class="section-head">
        <div>
            <h1>Create Bungalow</h1>
            <p class="muted">Add a new property for customers to book.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form class="stack" method="POST" action="{{ route('admin.bungalows.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.bungalows.partials.form')
                <button class="button" type="submit">Create bungalow</button>
            </form>
        </div>
    </div>
</x-layouts.app>
