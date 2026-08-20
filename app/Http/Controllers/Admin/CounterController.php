<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::with(['serviceType', 'operator'])->get();
        return view('admin.counters.index', compact('counters'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $operators = User::where('role', 'operator')->get();
        return view('admin.counters.create', compact('serviceTypes', 'operators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'service_type_id' => 'required|exists:service_types,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);

        $validated['institution_id'] = Institution::first()->id;
        $validated['status'] = $request->status ?? 'active';

        Counter::create($validated);

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter created successfully.');
    }

    public function edit(Counter $counter)
    {
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $operators = User::where('role', 'operator')->get();
        return view('admin.counters.edit', compact('counter', 'serviceTypes', 'operators'));
    }

    public function update(Request $request, Counter $counter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'service_type_id' => 'required|exists:service_types,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:active,inactive,maintenance',
        ]);

        $counter->update($validated);

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter updated successfully.');
    }

    public function destroy(Counter $counter)
    {
        $counter->delete();

        return redirect()->route('admin.counters.index')
            ->with('success', 'Counter deleted successfully.');
    }

    public function checkNumber(Request $request)
    {
        $number = $request->get('number');
        $excludeId = $request->get('exclude_id');
        
        $query = Counter::where('number', $number);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }
}
