<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;

class PolicyController extends Controller
{
    /**
     * Display the edit form for the given policy type.
     */
    public function edit($type)
    {
        // Validate allowed types
        if (!in_array($type, ['terms', 'privacy', 'refund'])) {
            abort(404);
        }

        // Retrieve the policy by type; if not exists, create a default record.
        $policy = Policy::firstOrCreate(
            ['type' => $type],
            ['title' => $this->getTitleByType($type), 'content' => '']
        );

        return view('AdminPanel.policies.edit', compact('policy'));
    }

    /**
     * Update the policy content.
     */
    public function update(Request $request, $type)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $policy = Policy::where('type', $type)->firstOrFail();
        $policy->update(['content' => $request->content]);

        return redirect()->route('policies.edit', $type)
            ->with('success', 'Policy updated successfully.');
    }

    /**
     * Helper function to determine title based on type.
     */
    protected function getTitleByType($type)
    {
        switch ($type) {
            case 'terms':
                return 'Terms & Conditions';
            case 'privacy':
                return 'Privacy Policy';
            case 'refund':
                return 'Refund Policy';
            default:
                return 'Policy';
        }
    }
}
