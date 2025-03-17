<?php

namespace App\Http\Controllers;

use App\Models\SystemUpdate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;

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
        try {
            $updates = SystemUpdate::latest('published_at')->paginate(10);
            return view('system-updates.index', compact('updates'));
        } catch (Exception $e) {
            Log::error('Error in system updates index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading system updates. Please try again.');
        }
    }

    /**
     * Show the form for creating a new system update.
     */
    public function create()
    {
        try {
            return view('system-updates.create');
        } catch (Exception $e) {
            Log::error('Error in system updates create: ' . $e->getMessage());
            return redirect()->route('system-updates.index')->with('error', 'An error occurred while loading the create form. Please try again.');
        }
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

            $validated['published_at'] = Carbon::parse($validated['published_at']);
            $validated['is_active'] = $request->has('is_active');

            SystemUpdate::create($validated);

            return redirect()->route('system-updates.index')
                ->with('success', 'System update created successfully.');
        } catch (QueryException $e) {
            Log::error('Database error in system updates store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error occurred. Please check your data and try again.');
        } catch (Exception $e) {
            Log::error('Error in system updates store: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving the system update. Please try again.');
        }
    }

    /**
     * Display the specified system update.
     */
    public function show(SystemUpdate $systemUpdate)
    {
        try {
            return view('system-updates.show', compact('systemUpdate'));
        } catch (Exception $e) {
            Log::error('Error in system updates show: ' . $e->getMessage());
            return redirect()->route('system-updates.index')->with('error', 'An error occurred while loading the system update details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified system update.
     */
    public function edit(SystemUpdate $systemUpdate)
    {
        try {
            return view('system-updates.edit', compact('systemUpdate'));
        } catch (Exception $e) {
            Log::error('Error in system updates edit: ' . $e->getMessage());
            return redirect()->route('system-updates.index')->with('error', 'An error occurred while loading the edit form. Please try again.');
        }
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

            $validated['published_at'] = Carbon::parse($validated['published_at']);
            $validated['is_active'] = $request->has('is_active');

            $systemUpdate->update($validated);

            return redirect()->route('system-updates.index')
                ->with('success', 'System update updated successfully.');
        } catch (QueryException $e) {
            Log::error('Database error in system updates update: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error occurred. Please check your data and try again.');
        } catch (Exception $e) {
            Log::error('Error in system updates update: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the system update. Please try again.');
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
        } catch (Exception $e) {
            Log::error('Error in system updates destroy: ' . $e->getMessage());
            return redirect()->route('system-updates.index')->with('error', 'An error occurred while deleting the system update. Please try again.');
        }
    }
}
