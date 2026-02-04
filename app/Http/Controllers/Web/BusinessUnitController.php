<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;

class BusinessUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessUnits = BusinessUnit::orderBy('name')->paginate(10);
        return view('admin.master.business_units.index', compact('businessUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master.business_units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        BusinessUnit::create($request->all());

        return redirect()->route('master.business-units.index')->with('success', 'Business Unit created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessUnit $businessUnit)
    {
        return view('admin.master.business_units.edit', compact('businessUnit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessUnit $businessUnit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $businessUnit->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('master.business-units.index')->with('success', 'Business Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessUnit $businessUnit)
    {
        $businessUnit->delete();
        return redirect()->route('master.business-units.index')->with('success', 'Business Unit deleted successfully.');
    }
}
