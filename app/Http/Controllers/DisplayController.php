<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\DisplaySetting;
use App\Models\MediaContent;
use App\Models\Queue;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    public function index()
    {
        // 1. Get all active counters with their current serving queue
        $counters = Counter::where('status', 'active')
            ->with(['serviceType', 'queues' => function($q) {
                $q->today()->active()->latest('called_at');
            }])
            ->get();

        // 2. Get display settings
        $settings = DisplaySetting::pluck('value', 'key')->toArray();
        if (isset($settings['company_name'])) {
            $settings['name'] = $settings['company_name'];
        }

        // 3. Get media contents (videos/images)
        $media = MediaContent::all();

        // 4. Get last called queue for the "BIG" display area
        $lastCalled = Queue::today()
            ->active()
            ->with(['serviceType', 'counter'])
            ->latest('called_at')
            ->first();

        $layout = $settings['display_layout'] ?? 'default';
        if ($layout === 'list_counter') {
            return view('display.list_counter', compact('counters', 'settings', 'media', 'lastCalled'));
        }
        if ($layout === 'imigrasi') {
            return view('display.imigrasi', compact('counters', 'settings', 'media', 'lastCalled'));
        }
        if ($layout === 'lounge') {
            return view('display.lounge', compact('counters', 'settings', 'media', 'lastCalled'));
        }

        return view('display.index', compact('counters', 'settings', 'media', 'lastCalled'));
    }
}
