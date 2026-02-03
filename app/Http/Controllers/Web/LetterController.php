<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LetterController extends Controller
{
    /**
     * Display a listing of letters.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // If has approval permission, show all relevant letters
        // Otherwise show only letters created by user
        $query = Letter::with(['creator', 'template'])->latest();

        if (!$user->role->can_approve && !$user->role->is_admin) {
            $query->where('creator_id', $user->id);
        }

        $letters = $query->paginate(10);

        return view('letters.index', compact('letters'));
    }

    /**
     * Show the form for creating a new letter.
     */
    public function create()
    {
        $templates = LetterTemplate::all();
        return view('letters.create', compact('templates'));
    }

    /**
     * Store a newly created letter in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:letter_templates,id',
            'recipient_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            // Flexible keys for template placeholders
            'data' => 'array', 
        ]);

        $template = LetterTemplate::findOrFail($request->template_id);
        $content = $template->content;
        $user = $request->user();

        // Basic Replacement Logic
        $replacements = [
            '[RECIPIENT_NAME]' => $request->recipient_name,
            '[DATE]' => now()->format('d F Y'),
            '[CREATOR_NAME]' => $user->name,
            '[CREATOR_POSITION]' => $user->position ?? 'HR Staff',
        ];

        // Add dynamic form data substitutions
        if ($request->has('data')) {
            foreach ($request->data as $key => $value) {
                $replacements['[' . strtoupper($key) . ']'] = $value;
            }
        }

        // Perform replacement
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        Letter::create([
            'template_id' => $template->id,
            'creator_id' => $user->id,
            'recipient_name' => $request->recipient_name,
            'subject' => $request->subject,
            'content' => $content,
            'status' => 'pending', // Directly pending for MVP explanation
        ]);

        return redirect()->route('letters.index')->with('success', 'Letter drafted successfully.');
    }

    /**
     * Display the specified letter.
     */
    public function show(Letter $letter)
    {
        return view('letters.show', compact('letter'));
    }

    /**
     * Approve the letter and generate number.
     */
    public function approve(Request $request, Letter $letter)
    {
        $this->authorize('approve', $letter); // Requires Policy

        $user = $request->user();
        
        // Generate Number: NO/CODE/MONTH/YEAR
        // Example: 001/SP1/II/2026
        // Simple counter logic for MVP
        $count = Letter::whereYear('created_at', now()->year)->where('status', 'approved')->count() + 1;
        $number = sprintf('%03d/%s/%s/%s', $count, $letter->template->code, $this->toRoman(now()->month), now()->year);

        // Replace number placeholder in content if exists
        $content = str_replace('[LETTER_NUMBER]', $number, $letter->content);

        $letter->update([
            'status' => 'approved',
            'letter_number' => $number,
            'content' => $content,
            'approver_id' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Letter approved and number generated: ' . $number);
    }
    
    /**
     * Reject Letter
     */
    public function reject(Request $request, Letter $letter)
    {
        $this->authorize('approve', $letter);
        
        $letter->update([
           'status' => 'rejected',
           'rejection_note' => $request->note ?? 'Rejected by ' . $request->user()->name
        ]);
        
        return back()->with('success', 'Letter rejected.');
    }

    /**
     * Helper to convert number to Roman
     */
    private function toRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
