<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBungalowRequest;
use App\Http\Requests\UpdateBungalowRequest;
use App\Models\Amenity;
use App\Models\Bungalow;
use Illuminate\Http\RedirectResponse;
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
        unset($data['amenity_ids']);

        $bungalow = Bungalow::create($data);
        $bungalow->amenities()->sync($amenityIds);

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow created.');
    }

    public function edit(Bungalow $bungalow): View
    {
        $bungalow->load('amenities');

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
        unset($data['amenity_ids']);

        $bungalow->update($data);
        $bungalow->amenities()->sync($amenityIds);

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow updated.');
    }

    public function destroy(Bungalow $bungalow): RedirectResponse
    {
        $bungalow->delete();

        return back()->with('status', 'Bungalow deleted.');
    }
}
