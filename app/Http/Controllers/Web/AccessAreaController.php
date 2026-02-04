<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessArea;

class AccessAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accessAreas = AccessArea::orderBy('name')->paginate(10);
        return view('admin.master.access_areas.index', compact('accessAreas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master.access_areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        AccessArea::create($request->all());

        return redirect()->route('master.access-areas.index')->with('success', 'Access Area created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessArea $accessArea)
    {
        return view('admin.master.access_areas.edit', compact('accessArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessArea $accessArea)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $accessArea->update($request->all());

        return redirect()->route('master.access-areas.index')->with('success', 'Access Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessArea $accessArea)
    {
        $accessArea->delete();
        return redirect()->route('master.access-areas.index')->with('success', 'Access Area deleted successfully.');
    }
}
