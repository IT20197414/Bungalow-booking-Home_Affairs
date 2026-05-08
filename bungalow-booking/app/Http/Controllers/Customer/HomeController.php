<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bungalow;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredBungalows = Bungalow::query()
            ->with('primaryImage')
            ->where('status', 'available')
            ->where('featured', true)
            ->latest()
            ->take(6)
            ->get();

        return view('customer.home', compact('featuredBungalows'));
    }
}
