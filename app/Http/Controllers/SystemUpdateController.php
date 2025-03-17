<?php

namespace App\Http\Controllers;

use App\Models\SystemUpdate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SystemUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super Admin');
    }

    /**
     * Display a listing of system updates.
     */
    public function index()
    {
        $updates = SystemUpdate::latest('published_at')->paginate(10);
        return view('system-updates.index', compact('updates'));
    }

    /**
     * Show the form for creating a new system update.
     */
    public function create()
    {
        return view('system-updates.create');
    }

    /**
     * Store a newly created system update in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'published_at' => 'required|date',
            'is_active' => 'boolean',
            'author_id' => 'required|exists:users,id'
        ]);

        $validated['published_at'] = Carbon::parse($validated['published_at']);
        $validated['is_active'] = $request->has('is_active');

        SystemUpdate::create($validated);

        return redirect()->route('system-updates.index')
            ->with('success', 'System update created successfully.');
    }

    /**
     * Display the specified system update.
     */
    public function show(SystemUpdate $systemUpdate)
    {
        return view('system-updates.show', compact('systemUpdate'));
    }

    /**
     * Show the form for editing the specified system update.
     */
    public function edit(SystemUpdate $systemUpdate)
    {
        return view('system-updates.edit', compact('systemUpdate'));
    }

    /**
     * Update the specified system update in storage.
     */
    public function update(Request $request, SystemUpdate $systemUpdate)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'published_at' => 'required|date',
            'is_active' => 'boolean',
            'author_id' => 'required|exists:users,id'
        ]);

        $validated['published_at'] = Carbon::parse($validated['published_at']);
        $validated['is_active'] = $request->has('is_active');

        $systemUpdate->update($validated);

        return redirect()->route('system-updates.index')
            ->with('success', 'System update updated successfully.');
    }

    /**
     * Remove the specified system update from storage.
     */
    public function destroy(SystemUpdate $systemUpdate)
    {
        $systemUpdate->delete();

        return redirect()->route('system-updates.index')
            ->with('success', 'System update deleted successfully.');
    }
}
