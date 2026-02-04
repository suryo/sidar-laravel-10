<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of holidays.
     */
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(10);
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
            'is_national_holiday' => 'boolean',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    /**
     * Remove the specified holiday from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday removed successfully.');
    }
}
