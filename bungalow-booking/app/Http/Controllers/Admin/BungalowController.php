<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBungalowRequest;
use App\Http\Requests\UpdateBungalowRequest;
use App\Models\Amenity;
use App\Models\Bungalow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BungalowController extends Controller
{
    public function index(): View
    {
        return view('admin.bungalows.index', [
            'bungalows' => Bungalow::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.bungalows.create', [
            'bungalow' => new Bungalow(['status' => 'available', 'capacity' => 2, 'bedrooms' => 1, 'bathrooms' => 1]),
            'amenities' => Amenity::orderBy('name')->get(),
            'selectedAmenityIds' => [],
        ]);
    }

    public function store(StoreBungalowRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $amenityIds = $data['amenity_ids'] ?? [];
        $photos = $data['photos'] ?? [];
        unset($data['amenity_ids'], $data['photos']);

        $bungalow = Bungalow::create($data);
        $bungalow->amenities()->sync($amenityIds);
        $this->storePhotos($bungalow, $photos);

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow created.');
    }

    public function edit(Bungalow $bungalow): View
    {
        $bungalow->load('amenities', 'images');

        return view('admin.bungalows.edit', [
            'bungalow' => $bungalow,
            'amenities' => Amenity::orderBy('name')->get(),
            'selectedAmenityIds' => $bungalow->amenities->pluck('id')->all(),
        ]);
    }

    public function update(UpdateBungalowRequest $request, Bungalow $bungalow): RedirectResponse
    {
        $data = $request->validated();
        $amenityIds = $data['amenity_ids'] ?? [];
        $photos = $data['photos'] ?? [];
        $deleteImageIds = $data['delete_image_ids'] ?? [];
        $primaryImageId = $data['primary_image_id'] ?? null;
        unset($data['amenity_ids'], $data['photos'], $data['delete_image_ids'], $data['primary_image_id']);

        $bungalow->update($data);
        $bungalow->amenities()->sync($amenityIds);
        $this->deletePhotos($bungalow, $deleteImageIds);
        $this->storePhotos($bungalow, $photos);
        $this->setPrimaryPhoto($bungalow, $primaryImageId);
        $this->ensurePrimaryPhoto($bungalow);

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow updated.');
    }

    public function destroy(Bungalow $bungalow): RedirectResponse
    {
        foreach ($bungalow->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $bungalow->delete();

        return back()->with('status', 'Bungalow deleted.');
    }

    /**
     * @param  array<int, UploadedFile>  $photos
     */
    private function storePhotos(Bungalow $bungalow, array $photos): void
    {
        if ($photos === []) {
            return;
        }

        $hasPrimaryImage = $bungalow->images()->where('is_primary', true)->exists();
        $nextSortOrder = (int) $bungalow->images()->max('sort_order') + 1;

        foreach ($photos as $index => $photo) {
            $bungalow->images()->create([
                'path' => $photo->store('bungalows', 'public'),
                'caption' => $bungalow->title,
                'is_primary' => ! $hasPrimaryImage && $index === 0,
                'sort_order' => $nextSortOrder + $index,
            ]);
        }
    }

    /**
     * @param  array<int, int>  $imageIds
     */
    private function deletePhotos(Bungalow $bungalow, array $imageIds): void
    {
        if ($imageIds === []) {
            return;
        }

        $images = $bungalow->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    private function setPrimaryPhoto(Bungalow $bungalow, ?int $imageId): void
    {
        if ($imageId === null || ! $bungalow->images()->whereKey($imageId)->exists()) {
            return;
        }

        $bungalow->images()->update(['is_primary' => false]);
        $bungalow->images()->whereKey($imageId)->update(['is_primary' => true]);
    }

    private function ensurePrimaryPhoto(Bungalow $bungalow): void
    {
        if ($bungalow->images()->where('is_primary', true)->exists()) {
            return;
        }

        $firstImage = $bungalow->images()->orderBy('sort_order')->first();

        if ($firstImage) {
            $firstImage->update(['is_primary' => true]);
        }
    }
}
