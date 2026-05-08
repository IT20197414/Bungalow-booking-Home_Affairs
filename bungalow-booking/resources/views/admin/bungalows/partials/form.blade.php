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
    <label>Nightly rate
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
        <input type="time" name="check_in_time" value="{{ old('check_in_time', $bungalow->check_in_time) }}">
    </label>
    <label>Check out time
        <input type="time" name="check_out_time" value="{{ old('check_out_time', $bungalow->check_out_time) }}">
    </label>
</div>

<label style="display:flex;align-items:center;gap:8px;font-weight:500">
    <input style="width:auto" type="checkbox" name="featured" value="1" @checked(old('featured', $bungalow->featured))> Featured
</label>
