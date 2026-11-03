<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourtRequest;
use App\Http\Requests\Admin\UpdateCourtRequest;
use App\Models\Court;
use App\Models\Venue;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourtController extends Controller
{
    public function index(): View
    {
        $courts = Court::with('venue')->orderBy('name')->paginate(15);

        return view('admin.courts.index', compact('courts'));
    }

    public function create(): View
    {
        $venues = Venue::orderBy('name')->pluck('name', 'id');

        return view('admin.courts.create', compact('venues'));
    }

    public function store(StoreCourtRequest $request): RedirectResponse
    {
        Court::create($request->validated());

        return redirect()->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Court $court): View
    {
        $venues = Venue::orderBy('name')->pluck('name', 'id');

        return view('admin.courts.edit', compact('court', 'venues'));
    }

    public function update(UpdateCourtRequest $request, Court $court): RedirectResponse
    {
        $court->update($request->validated());

        return redirect()->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Court $court): RedirectResponse
    {
        try {
            // TODO: cek booking setelah tabel bookings dibuat
            $court->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.courts.index')
                ->with('error', 'Lapangan tidak dapat dihapus karena masih memiliki booking.');
        }

        return redirect()->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
