<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBungalowRequest;
use App\Http\Requests\UpdateBungalowRequest;
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
        ]);
    }

    public function store(StoreBungalowRequest $request): RedirectResponse
    {
        Bungalow::create($request->validated());

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow created.');
    }

    public function edit(Bungalow $bungalow): View
    {
        return view('admin.bungalows.edit', compact('bungalow'));
    }

    public function update(UpdateBungalowRequest $request, Bungalow $bungalow): RedirectResponse
    {
        $bungalow->update($request->validated());

        return redirect()->route('admin.bungalows.index')->with('status', 'Bungalow updated.');
    }

    public function destroy(Bungalow $bungalow): RedirectResponse
    {
        $bungalow->delete();

        return back()->with('status', 'Bungalow deleted.');
    }
}
