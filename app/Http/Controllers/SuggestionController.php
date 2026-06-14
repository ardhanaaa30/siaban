<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the suggestions.
     */
    public function index()
    {
        $suggestions = Suggestion::with('user')->latest()->get();
        return view('saran', compact('suggestions'));
    }

    /**
     * Store a newly created suggestion in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ]);

        Suggestion::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'content' => $request->content,
        ]);

        return back()->with('status', 'saran-terkirim');
    }
}
