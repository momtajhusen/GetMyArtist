<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socials = Social::orderBy('created_at', 'desc')->get();
        return view('AdminPanel.Socials.socials', compact('socials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AdminPanel.Socials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'icon'    => 'required|string|max:255',
            'url'     => 'required|url',
            'contact' => 'required|string|max:255',
        ]);

        Social::create($request->only(['icon', 'url', 'contact']));

        return redirect()->route('socials.index')->with('success', 'Social created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Social $social)
    {
        return view('AdminPanel.Socials.show', compact('social'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Social $social)
    {
        return view('AdminPanel.Socials.edit', compact('social'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Social $social)
    {
        $request->validate([
            'icon'    => 'required|string|max:255',
            'url'     => 'required|url',
            'contact' => 'required|string|max:255',
        ]);

        $social->update($request->only(['icon', 'url', 'contact']));

        return redirect()->route('socials.index')->with('success', 'Social updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Social $social)
    {
        $social->delete();
        return redirect()->route('socials.index')->with('success', 'Social deleted successfully.');
    }
}
