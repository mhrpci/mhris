<?php

namespace App\Http\Controllers;

use App\Models\SystemUpdate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'published_at' => 'required|date',
                'is_active' => 'boolean',
                'author_id' => 'required|exists:users,id'
            ]);

            // Ensure published_at is properly formatted
            try {
                $validated['published_at'] = Carbon::parse($validated['published_at']);
            } catch (\Exception $e) {
                Log::error('Date parsing error: ' . $e->getMessage());
                return back()
                    ->withInput()
                    ->withErrors(['published_at' => 'Invalid date format']);
            }

            // Ensure is_active is a boolean
            $validated['is_active'] = $request->has('is_active') ? true : false;

            SystemUpdate::create($validated);

            return redirect()->route('system-updates.index')
                ->with('success', 'System update created successfully.');
        } catch (\Exception $e) {
            Log::error('System update creation error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the system update. Please try again.']);
        }
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
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'published_at' => 'required|date',
                'is_active' => 'boolean',
                'author_id' => 'required|exists:users,id'
            ]);

            // Ensure published_at is properly formatted
            try {
                $validated['published_at'] = Carbon::parse($validated['published_at']);
            } catch (\Exception $e) {
                Log::error('Date parsing error: ' . $e->getMessage());
                return back()
                    ->withInput()
                    ->withErrors(['published_at' => 'Invalid date format']);
            }

            // Ensure is_active is a boolean
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $systemUpdate->update($validated);

            return redirect()->route('system-updates.index')
                ->with('success', 'System update updated successfully.');
        } catch (\Exception $e) {
            Log::error('System update update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the system update. Please try again.']);
        }
    }

    /**
     * Remove the specified system update from storage.
     */
    public function destroy(SystemUpdate $systemUpdate)
    {
        try {
            $systemUpdate->delete();
            return redirect()->route('system-updates.index')
                ->with('success', 'System update deleted successfully.');
        } catch (\Exception $e) {
            Log::error('System update deletion error: ' . $e->getMessage());
            return redirect()->route('system-updates.index')
                ->with('error', 'An error occurred while deleting the system update.');
        }
    }
}
