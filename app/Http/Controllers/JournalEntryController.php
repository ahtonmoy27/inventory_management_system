<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    public function index(Request $request): View
    {
        $query = JournalEntry::with('lines.account');

        if ($request->filled('start_date')) {
            $query->whereDate('entry_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('entry_date', '<=', $request->end_date);
        }

        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }

        $journalEntries = $query->latest()->paginate(15);

        return view('journal-entries.index', compact('journalEntries'));
    }

    public function show(JournalEntry $journalEntry): View
    {
        $journalEntry->load('lines.account');
        return view('journal-entries.show', compact('journalEntry'));
    }
}
