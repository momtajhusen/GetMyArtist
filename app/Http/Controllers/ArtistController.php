<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::all();
        return view('AdminPanel.artists.index', compact('artists'));
    }

    public function create()
    {
        return view('AdminPanel.artists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'            => 'required|exists:users,id',
            'category_id'        => 'nullable|exists:categories,id',
            'stage_name'         => 'nullable|string|max:255',
            'profile_managed_by' => 'required|string',
            'contact_first_name' => 'nullable|string|max:255',
            'contact_last_name'  => 'nullable|string|max:255',
            'bio'                => 'nullable|string',
            'profile_photo'      => 'nullable|string',
            'is_premium'         => 'boolean',
            'social_links'       => 'nullable|array',
            'experience_years'   => 'nullable|integer',
            'portfolio'          => 'nullable|array',
            'genre'              => 'nullable|array',
            'events'             => 'nullable|array',
            'booking_rate'       => 'nullable|numeric',
            'location'           => 'nullable|string|max:255',
            'awards'             => 'nullable|array',
            'is_verified'        => 'boolean',
        ]);
    
        Artist::create($data);
    
        return redirect()->route('artists.index')->with('success', 'Artist created successfully.');
    }
    

    public function show(Artist $artist)
    {
        return view('AdminPanel.artists.show', compact('artist'));
    }

    public function edit(Artist $artist)
    {
        return view('AdminPanel.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $data = $request->validate([
            'category_id'        => 'nullable|exists:categories,id',
            'stage_name'         => 'nullable|string|max:255',
            'profile_managed_by' => 'required|string',
            'contact_first_name' => 'nullable|string|max:255',
            'contact_last_name'  => 'nullable|string|max:255',
            'bio'                => 'nullable|string',
            'profile_photo'      => 'nullable|string',
            'is_premium'         => 'boolean',
            'social_links'       => 'nullable|array',
            'experience_years'   => 'nullable|integer',
            'portfolio'          => 'nullable|array',
            'genre'              => 'nullable|array',
            'events'             => 'nullable|array',
            'booking_rate'       => 'nullable|numeric',
            'location'           => 'nullable|string|max:255',
            'awards'             => 'nullable|array',
            'is_verified'        => 'boolean',
        ]);
    
        $artist->update($data);
    
        return redirect()->route('artists.index')->with('success', 'Artist updated successfully.');
    }
    

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return redirect()->route('artists.index')->with('success', 'Artist deleted successfully.');
    }

    public function approve(Request $request, Artist $artist)
    {
        $artist->update(['approved' => true]);
        return redirect()->back()->with('success', 'Artist approved successfully.');
    }

    public function reject(Request $request, Artist $artist)
    {
        $artist->update(['approved' => false]);
        return redirect()->back()->with('success', 'Artist rejected successfully.');
    }

    public function updateSubscription(Request $request, Artist $artist)
    {
        $data = $request->validate([
            'plan_id'    => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date'
        ]);

        $artist->update(['subscription_plan_id' => $data['plan_id']]);
        return redirect()->back()->with('success', 'Artist subscription updated successfully.');
    }

    /**
     * Display the form for completing/editing the artist profile.
     */
    public function completeProfile()
    {
        // Artist profile may or may not exist; load if available.
        $artist = Artist::where('user_id', Auth::id())->first();
        // Load all categories for dropdown (if needed)
        $categories = Categories::all();
        return view('ArtistPanel.profile.complete-profile', compact('artist', 'categories'));
    }

    /**
     * Store or update the artist profile details.
     */
    public function storeProfile(Request $request)
    {
        $data = $request->validate([
            'stage_name'          => 'nullable|string|max:255',
            'profile_photo'       => 'nullable|image|max:2048',
            'experience_years'    => 'nullable|integer',
            'is_premium'          => 'nullable|boolean',
            'booking_rate'        => 'nullable|numeric',
            'location'            => 'nullable|string|max:255',
            'bio'                 => 'nullable|string',
            'profile_managed_by'  => 'required|in:artist,manager,agency,family',
            'contact_first_name'  => 'nullable|string|max:255',
            'contact_last_name'   => 'nullable|string|max:255',
            'phone'               => 'required|string',
            'email'               => 'required|string',
            'category_id'         => 'nullable|exists:categories,id',
            'genre'               => 'nullable|array',
            'events'              => 'nullable|array',
        ]);
    
        // Handle file upload if provided
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
    
        // Check if artist profile already exists for current user
        $artist = Artist::where('user_id', Auth::id())->first();
    
        if ($artist) {
            $artist->update($data);
        } else {
            $data['user_id'] = Auth::id();
            Artist::create($data);
        }
    
        return redirect()->route('artist.dashboard')->with('success', 'Your profile has been updated successfully.');
    }
    
    
}
