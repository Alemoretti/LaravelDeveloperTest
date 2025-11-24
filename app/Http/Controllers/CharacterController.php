<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    /**
     * Display a listing of characters with pagination and search.
     */
    public function index(Request $request): View
    {
        $query = Character::with('homeworld');

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('gender', 'like', "%{$search}%")
                    ->orWhere('birth_year', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        // Filter by homeworld
        if ($homeworldId = $request->input('homeworld_id')) {
            $query->where('homeworld_id', $homeworldId);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $characters = $query->paginate(15)->withQueryString();

        return view('characters.index', compact('characters'));
    }

    /**
     * Display the specified character with all relationships.
     */
    public function show(Character $character): View
    {
        $character->load('homeworld');

        return view('characters.show', compact('character'));
    }
}
