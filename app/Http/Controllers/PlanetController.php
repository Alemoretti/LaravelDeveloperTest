<?php

namespace App\Http\Controllers;

use App\Models\Planet;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PlanetController extends Controller
{
    /**
     * Display a listing of planets with pagination and search.
     */
    public function index(Request $request): View
    {
        $query = Planet::withCount('characters');

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('climate', 'like', "%{$search}%")
                    ->orWhere('terrain', 'like', "%{$search}%");
            });
        }

        // Filter by climate
        if ($climate = $request->input('climate')) {
            $query->where('climate', 'like', "%{$climate}%");
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $planets = $query->paginate(15)->withQueryString();

        return view('planets.index', compact('planets'));
    }

    /**
     * Display the specified planet with all relationships.
     */
    public function show(Planet $planet): View
    {
        $planet->loadCount('characters');
        $residents = $planet->characters()->paginate(10);

        return view('planets.show', compact('planet', 'residents'));
    }
}
