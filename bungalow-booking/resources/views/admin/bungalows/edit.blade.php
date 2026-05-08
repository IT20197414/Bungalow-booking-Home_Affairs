<x-layouts.app :title="'Edit '.$bungalow->title">
    <div class="section-head">
        <div>
            <h1>Edit Bungalow</h1>
            <p class="muted">{{ $bungalow->title }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form class="stack" method="POST" action="{{ route('admin.bungalows.update', $bungalow) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.bungalows.partials.form')
                <button class="button" type="submit">Save changes</button>
            </form>
        </div>
    </div>
</x-layouts.app>
