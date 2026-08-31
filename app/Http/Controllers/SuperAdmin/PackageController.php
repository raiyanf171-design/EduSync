<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display all packages
     */
    public function index()
    {
        $packages = Package::paginate(15);
        return view('super-admin.packages.index', compact('packages'));
    }

    /**
     * Show package creation form
     */
    public function create()
    {
        return view('super-admin.packages.create');
    }

    /**
     * Store new package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_name' => 'required|string|max:255',
            'student_capacity' => 'required|integer|min:10',
            'price_1year' => 'required|numeric|min:0',
            'price_2year' => 'required|numeric|min:0',
            'features' => 'nullable|json',
        ]);

        $validated['status'] = 'active';

        Package::create($validated);

        return redirect()->route('super-admin.packages.index')
            ->with('success', 'Package created successfully');
    }

    /**
     * Show package details
     */
    public function show(Package $package)
    {
        return view('super-admin.packages.show', compact('package'));
    }

    /**
     * Show package edit form
     */
    public function edit(Package $package)
    {
        return view('super-admin.packages.edit', compact('package'));
    }

    /**
     * Update package
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'package_name' => 'required|string|max:255',
            'student_capacity' => 'required|integer|min:10',
            'price_1year' => 'required|numeric|min:0',
            'price_2year' => 'required|numeric|min:0',
            'features' => 'nullable|json',
        ]);

        $package->update($validated);

        return redirect()->route('super-admin.packages.show', $package)
            ->with('success', 'Package updated successfully');
    }
}
