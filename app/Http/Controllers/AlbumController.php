<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    // List All Albums
    public function index()
    {
        $albums = Album::latest()->get();
        return view('albums.index', compact('albums'));
    }

    // Show Create Form
    public function create()
    {
        return view('albums.create');
    }

    // Store Album
    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'media_type' => 'required|in:image,video,youtube,cloud',
            'media' => 'nullable|file|max:50000',
            'media_url' => 'nullable|url'
        ]);

        $data = $request->only(['artist_id', 'title', 'description', 'media_type', 'store', 'status']);

        if ($request->media_type === 'youtube' || $request->media_type === 'cloud') {
            $data['media_url'] = $request->media_url;
        } elseif ($request->hasFile('media')) {
            $path = $request->file('media')->store('public/uploads');
            $data['storage_path'] = $path;
            $data['size'] = $request->file('media')->getSize();
        }

        Album::create($data);
        return redirect()->route('albums.index')->with('success', 'Album Created Successfully');
    }
}
