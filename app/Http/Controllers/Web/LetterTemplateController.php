<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;

class LetterTemplateController extends Controller
{
    /**
     * Display a listing of templates.
     */
    public function index()
    {
        $templates = LetterTemplate::all();
        return view('letter_templates.index', compact('templates'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(LetterTemplate $letterTemplate)
    {
        return view('letter_templates.edit', compact('letterTemplate'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, LetterTemplate $letterTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:letter_templates,code,' . $letterTemplate->id,
            'content' => 'required|string',
        ]);

        $letterTemplate->update($request->only(['name', 'code', 'content']));

        return redirect()->route('letter-templates.index')->with('success', 'Template updated successfully.');
    }
}
