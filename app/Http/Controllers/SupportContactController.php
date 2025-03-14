<?php

namespace App\Http\Controllers;

use App\Models\SupportContact;
use Illuminate\Http\Request;

class SupportContactController extends Controller
{
    /**
     * Display a listing of the support contacts.
     */
    public function index()
    {
        $contacts = SupportContact::with('admin')->orderBy('created_at', 'desc')->get();
        return view('AdminPanel.support_contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new support contact.
     */
    public function create()
    {
        return view('support_contacts.create');
    }

    /**
     * Store a newly created support contact in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'type'     => 'required|string|max:50',
            'value'    => 'required|string|max:255',
        ]);

        SupportContact::create($data);

        return redirect()->route('support_contacts.index')->with('success', 'Support contact added successfully.');
    }

    /**
     * Display the specified support contact.
     */
    public function show(SupportContact $supportContact)
    {
        return view('support_contacts.show', compact('supportContact'));
    }

    /**
     * Show the form for editing the specified support contact.
     */
    public function edit(SupportContact $supportContact)
    {
        return view('support_contacts.edit', compact('supportContact'));
    }

    /**
     * Update the specified support contact in storage.
     */
    public function update(Request $request, SupportContact $supportContact)
    {
        $data = $request->validate([
            'admin_id' => 'required|exists:users,id',
            'type'     => 'required|string|max:50',
            'value'    => 'required|string|max:255',
        ]);

        $supportContact->update($data);

        return redirect()->route('support_contacts.index')->with('success', 'Support contact updated successfully.');
    }

    /**
     * Remove the specified support contact from storage.
     */
    public function destroy(SupportContact $supportContact)
    {
        $supportContact->delete();

        return redirect()->route('support_contacts.index')->with('success', 'Support contact deleted successfully.');
    }
}
