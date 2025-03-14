<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    /**
     * Display a listing of the FAQs.
     */
    public function index()
    {
        $faqs = Faq::orderBy('type')
                    ->orderBy('created_at', 'asc')  
                    ->get();
    
        return view('AdminPanel.faqs.index', compact('faqs'));
    }
    

    /**
     * Show the form for creating a new FAQ.
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'     => ['nullable', 'string', 'max:50'],
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'status'   => ['required', Rule::in(['published', 'draft'])],
            'audience' => ['required', Rule::in(['user', 'artist', 'both'])],
        ]);

        Faq::create($data);

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Display the specified FAQ.
     */
    public function show(Faq $faq)
    {
        return view('faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit(Faq $faq)
    {
        return view('faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'type'     => ['nullable', 'string', 'max:50'],
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'status'   => ['required', Rule::in(['published', 'draft'])],
            'audience' => ['required', Rule::in(['user', 'artist', 'both'])],
        ]);

        $faq->update($data);

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
