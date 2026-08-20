<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('sort_order')->get();
        return view('admin.service-types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('admin.service-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $institution = Institution::first();
        $validated['institution_id'] = $institution->id;
        $validated['is_active'] = $request->has('is_active');

        ServiceType::create($validated);

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type created successfully.');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('admin.service-types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $serviceType->update($validated);

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }

    public function checkCode(Request $request)
    {
        $code = $request->get('code');
        $excludeId = $request->get('exclude_id');
        
        $query = ServiceType::where('code', $code);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }
}
