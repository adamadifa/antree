<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisplaySetting;
use App\Models\MediaContent;
use App\Models\Institution;
use Illuminate\Http\Request;

class DisplaySettingController extends Controller
{
    public function index()
    {
        $institutionId = Institution::first()->id;
        
        $settings = DisplaySetting::where('institution_id', $institutionId)
            ->pluck('value', 'key');
            
        $mediaContents = MediaContent::where('institution_id', $institutionId)
            ->orderBy('sort_order')
            ->get();
            
        return view('admin.display-settings.index', compact('settings', 'mediaContents'));
    }

    public function updateSettings(Request $request)
    {
        $institutionId = Institution::first()->id;
        
        $request->validate([
            'logo_file' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->except(['_token', 'logo_file']);
        
        // Handle logo file upload
        if ($request->hasFile('logo_file')) {
            // Delete old file if exists
            $oldSetting = DisplaySetting::where('institution_id', $institutionId)->where('key', 'logo_url')->first();
            if ($oldSetting && $oldSetting->value) {
                $oldPath = public_path('storage/' . $oldSetting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            
            // Store new file in 'logos' directory under the public disk
            $path = $request->file('logo_file')->store('logos', 'public');
            $data['logo_url'] = $path;
        }
        
        foreach ($data as $key => $value) {
            DisplaySetting::updateOrCreate(
                ['institution_id' => $institutionId, 'key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->route('admin.display-settings.index')
            ->with('success', 'Display settings updated successfully.');
    }

    public function storeMedia(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:youtube,image,video,text',
            'content' => 'required|string',
            'sort_order' => 'integer'
        ]);

        $institutionId = Institution::first()->id;

        MediaContent::create([
            'institution_id' => $institutionId,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ]);

        return redirect()->route('admin.display-settings.index')
            ->with('success', 'Media content added successfully.');
    }

    public function destroyMedia(MediaContent $media)
    {
        $media->delete();
        return redirect()->route('admin.display-settings.index')
            ->with('success', 'Media content removed successfully.');
    }
}
