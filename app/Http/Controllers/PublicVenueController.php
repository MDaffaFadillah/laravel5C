<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicVenueController extends Controller
{
    public function index(Request $request): View
    {
        $validSportTypes = ['futsal', 'badminton'];
        $sportType = in_array($request->query('sport_type'), $validSportTypes, true)
            ? $request->query('sport_type')
            : null;

        $search = $request->query('name');

        $venues = Venue::query()
            ->where('status', 'active')
            ->when($search, fn ($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->when($sportType, fn ($q) => $q->whereHas(
                'courts',
                fn ($q) => $q->where('sport_type', $sportType)
            ))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('venues.index', compact('venues', 'sportType', 'search'));
    }

    public function show(Venue $venue): View
    {
        if ($venue->status !== 'active') {
            abort(404);
        }

        $courts = $venue->courts()
            ->where('status', 'available')
            ->orderBy('sport_type')
            ->orderBy('name')
            ->get();

        return view('venues.show', compact('venue', 'courts'));
    }
}
