<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Categories;
use App\Models\Album;
use App\Models\User;
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
    

    public function show(Request $request, $id)
    {
        $artist = Artist::find($id);
    
        if (!$artist) {
            return redirect()->back()->with('error', 'Artist not found.');
        }
    
        $user = User::find($artist->user_id);
    
        return view('AdminPanel.Profile.index', compact('artist', 'user'));
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

    // Display all albums for the authenticated artist
    public function albumsIndex()
    {
        $artist = auth()->user();
        $albums = Album::where('artist_id', $artist->id)->get();
        return view('ArtistPanel.Albums.index', compact('albums'));
    }

    // Show the album upload form
    public function uploadAlbumForm()
    {
        return view('ArtistPanel.Albums.upload');
    }

    // Store album based on upload type (local or url)
    public function storeAlbum(Request $request)
    {
        // Validate common fields
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'upload_type' => 'required|in:local,url',
        ];
    
        if ($request->upload_type === 'local') {
            $rules['media_file'] = 'required|file';
        } elseif ($request->upload_type === 'url') {
            $rules['media_url'] = 'required|url';
        }
        $validatedData = $request->validate($rules);
    
        $album = new Album();
        $album->artist_id   = auth()->id();
        $album->title       = $validatedData['title'];
        $album->description = $validatedData['description'] ?? null;
    
        if ($request->upload_type === 'local') {
            $file = $request->file('media_file');
            $extension = strtolower($file->getClientOriginalExtension());
    
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv'];
    
            if (in_array($extension, $imageExtensions)) {
                $album->media_type = 'image';
            } elseif (in_array($extension, $videoExtensions)) {
                $album->media_type = 'video';
            } else {
                $album->media_type = 'image';
            }
            $path = $file->store('albums', 'public');
            $album->storage_path = $path;
        } elseif ($request->upload_type === 'url') {
            $mediaUrl = $validatedData['media_url'];
            $album->storage_path = $mediaUrl;
    
            $parsedPath = parse_url($mediaUrl, PHP_URL_PATH);
            $extension = strtolower(pathinfo($parsedPath, PATHINFO_EXTENSION));
    
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv'];
    
            // Define video hosting keywords
            $videoKeywords = ['youtube', 'youtu.be', 'vimeo', 'dailymotion'];
            $containsVideoKeyword = false;
            foreach ($videoKeywords as $keyword) {
                if (stripos($mediaUrl, $keyword) !== false) {
                    $containsVideoKeyword = true;
                    break;
                }
            }
    
            // If URL doesn't contain known video keywords, then it must have an extension.
            if (!$containsVideoKeyword && !$extension) {
                return redirect()->back()
                    ->withErrors(['media_url' => 'The URL must contain a valid file extension if it is not from a known video hosting site.'])
                    ->withInput();
            }
    
            // Set media_type based on extension if available, otherwise use video if keyword exists.
            if ($extension) {
                if (in_array($extension, $imageExtensions)) {
                    $album->media_type = 'image';
                } elseif (in_array($extension, $videoExtensions)) {
                    $album->media_type = 'video';
                } else {
                    $album->media_type = 'image'; // default fallback
                }
            } else {
                $album->media_type = $containsVideoKeyword ? 'video' : 'image';
            }
        }
    
        $album->save();
    
        return redirect()->route('artist.albums.index')->with('success', 'Album uploaded successfully.');
    }
    
}


