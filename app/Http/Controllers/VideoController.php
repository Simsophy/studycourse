<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'video_file' => 'nullable|mimes:mp4,mov,avi|max:51200'
        ]);

        if ($request->hasFile('video_file')) {
            $request->file('video_file')->store('videos', 'public');
        }

        return back()->with('success', 'Request processed successfully!');
    }
}