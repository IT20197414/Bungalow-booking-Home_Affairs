<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bungalow;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BungalowController extends Controller
{
    public function index(Request $request): View
    {
        $bungalows = Bungalow::query()
            ->with('primaryImage', 'amenities')
            ->where('status', 'available')
            ->when($request->integer('guests'), fn ($query, $guests) => $query->where('capacity', '>=', $guests))
            ->when($request->filled('city'), fn ($query) => $query->where('city', 'like', '%'.$request->string('city').'%'))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('customer.bungalows.index', compact('bungalows'));
    }

    public function show(Bungalow $bungalow): View
    {
        abort_unless($bungalow->status === 'available', 404);

        $bungalow->load('images', 'amenities', 'reviews.user');

        return view('customer.bungalows.show', compact('bungalow'));
    }
}
