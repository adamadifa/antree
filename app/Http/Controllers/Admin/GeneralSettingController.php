<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingController extends Controller
{
    /**
     * Display the general settings form.
     */
    public function index()
    {
        $institution = Institution::first();

        if (!$institution) {
            // Safe fallback if seeder was not run
            $institution = Institution::create([
                'name' => 'Antree Company',
                'app_name' => 'Antree',
                'is_active' => true
            ]);
        }

        return view('admin.general-settings.index', compact('institution'));
    }

    /**
     * Update the general settings.
     */
    public function update(Request $request)
    {
        $institution = Institution::first();

        if (!$institution) {
            return redirect()->back()->with('error', 'Institution record not found. Please seed the database first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'app_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'operating_hours' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only([
            'name',
            'app_name',
            'email',
            'phone',
            'address',
            'operating_hours',
            'footer_text'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($institution->logo_path) {
                // Strip public/storage or storage prefix to get original relative path
                $oldPath = str_replace('storage/', '', $institution->logo_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Save new logo
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = 'storage/' . $path;
        }

        $institution->update($data);

        return redirect()->route('admin.general-settings.index')
            ->with('success', 'General settings updated successfully.');
    }
}
