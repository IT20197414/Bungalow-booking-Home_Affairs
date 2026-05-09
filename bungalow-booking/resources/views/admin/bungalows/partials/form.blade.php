@php
    $checkInTime = old('check_in_time', $bungalow->check_in_time ? substr($bungalow->check_in_time, 0, 5) : null);
    $checkOutTime = old('check_out_time', $bungalow->check_out_time ? substr($bungalow->check_out_time, 0, 5) : null);
    $checkedAmenityIds = collect(old('amenity_ids', $selectedAmenityIds ?? []))->map(fn ($id) => (int) $id)->all();
@endphp

<label>Title
    <input name="title" value="{{ old('title', $bungalow->title) }}" required>
</label>

<label>Description
    <textarea name="description">{{ old('description', $bungalow->description) }}</textarea>
</label>

<div class="form-grid">
    <label>Address
        <input name="address" value="{{ old('address', $bungalow->address) }}">
    </label>
    <label>City
        <input name="city" value="{{ old('city', $bungalow->city) }}">
    </label>
</div>

<div class="stack">
    <strong>Google Map Location</strong>
    <div class="form-grid">
        <label>Latitude
            <input type="number" step="0.0000001" min="-90" max="90" name="latitude" value="{{ old('latitude', $bungalow->latitude) }}" placeholder="Example: 7.290572">
        </label>
        <label>Longitude
            <input type="number" step="0.0000001" min="-180" max="180" name="longitude" value="{{ old('longitude', $bungalow->longitude) }}" placeholder="Example: 80.633728">
        </label>
    </div>
    <p class="muted" style="margin:0">Add both values to show this bungalow on Google Maps for customers.</p>
</div>

<div class="form-grid">
    <label>Capacity
        <input type="number" min="1" name="capacity" value="{{ old('capacity', $bungalow->capacity) }}" required>
    </label>
    <label>Bedrooms
        <input type="number" min="1" name="bedrooms" value="{{ old('bedrooms', $bungalow->bedrooms) }}" required>
    </label>
    <label>Bathrooms
        <input type="number" min="1" name="bathrooms" value="{{ old('bathrooms', $bungalow->bathrooms) }}" required>
    </label>
    <label>Nightly rate (LKR)
        <input type="number" min="0" step="0.01" name="nightly_rate" value="{{ old('nightly_rate', $bungalow->nightly_rate) }}" required>
    </label>
</div>

<div class="form-grid">
    <label>Status
        <select name="status" required>
            @foreach(['available', 'maintenance', 'hidden'] as $status)
                <option value="{{ $status }}" @selected(old('status', $bungalow->status) === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>
    <label>Check in time
        <input type="time" name="check_in_time" value="{{ $checkInTime }}">
    </label>
    <label>Check out time
        <input type="time" name="check_out_time" value="{{ $checkOutTime }}">
    </label>
</div>

<label style="display:flex;align-items:center;gap:8px;font-weight:500">
    <input style="width:auto" type="checkbox" name="featured" value="1" @checked(old('featured', $bungalow->featured))> Featured
</label>

<div class="stack">
    <strong>Amenities</strong>
    <div class="form-grid">
        @forelse($amenities as $amenity)
            <label style="display:flex;align-items:center;gap:8px;font-weight:500">
                <input style="width:auto" type="checkbox" name="amenity_ids[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, $checkedAmenityIds))>
                {{ $amenity->name }}
            </label>
        @empty
            <p class="muted">No amenities have been added yet.</p>
        @endforelse
    </div>
</div>

<div class="stack">
    <strong>Photos</strong>
    @if($bungalow->exists && $bungalow->images->isNotEmpty())
        <div class="form-grid">
            @foreach($bungalow->images as $image)
                <div class="card">
                    <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->caption ?? $bungalow->title }}" style="width:100%;height:130px;object-fit:cover;display:block">
                    <div class="card-body stack">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500">
                            <input style="width:auto" type="radio" name="primary_image_id" value="{{ $image->id }}" @checked((int) old('primary_image_id', $image->is_primary ? $image->id : 0) === $image->id)>
                            Primary photo
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500;color:var(--accent)">
                            <input style="width:auto" type="checkbox" name="delete_image_ids[]" value="{{ $image->id }}" @checked(in_array($image->id, array_map('intval', old('delete_image_ids', []))))>
                            Remove this photo
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <label>Upload photos
        <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
    </label>
    <p class="muted" style="margin:0">You can upload up to 6 JPG, PNG, or WebP photos at a time.</p>
</div>
