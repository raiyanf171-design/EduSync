<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Package;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display all schools
     */
    public function index()
    {
        $schools = School::paginate(15);
        return view('super-admin.schools.index', compact('schools'));
    }

    /**
     * Show school creation form
     */
    public function create()
    {
        $packages = Package::active()->get();
        return view('super-admin.schools.create', compact('packages'));
    }

    /**
     * Store new school
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:schools',
            'package_id' => 'required|exists:packages,id',
            'bkash_phone' => 'required|regex:/^\d{11}$/',
        ]);

        School::create($validated);

        return redirect()->route('super-admin.schools.index')
            ->with('success', 'School created successfully');
    }

    /**
     * Show school details
     */
    public function show(School $school)
    {
        return view('super-admin.schools.show', compact('school'));
    }

    /**
     * Show school edit form
     */
    public function edit(School $school)
    {
        $packages = Package::active()->get();
        return view('super-admin.schools.edit', compact('school', 'packages'));
    }

    /**
     * Update school
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'bkash_phone' => 'required|regex:/^\d{11}$/',
            'status' => 'required|in:active,inactive',
        ]);

        $school->update($validated);

        return redirect()->route('super-admin.schools.show', $school)
            ->with('success', 'School updated successfully');
    }

    /**
     * Toggle school status
     */
    public function toggleStatus(School $school)
    {
        $school->update([
            'status' => $school->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'School status updated');
    }
}
