<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVenueRequest;
use App\Http\Requests\Admin\UpdateVenueRequest;
use App\Models\Venue;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VenueController extends Controller
{
    public function index(): View
    {
        $venues = Venue::orderBy('name')->paginate(15);

        return view('admin.venues.index', compact('venues'));
    }

    public function create(): View
    {
        return view('admin.venues.create');
    }

    public function store(StoreVenueRequest $request): RedirectResponse
    {
        Venue::create($request->validated());

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue berhasil ditambahkan.');
    }

    public function edit(Venue $venue): View
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(UpdateVenueRequest $request, Venue $venue): RedirectResponse
    {
        $venue->update($request->validated());

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue berhasil diperbarui.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        try {
            $venue->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.venues.index')
                ->with('error', 'Venue tidak dapat dihapus karena masih memiliki lapangan (court).');
        }

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue berhasil dihapus.');
    }
}
