<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'video_file' => 'required|mimes:mp4,mov,avi|max:51200'
        ]);

        $path = $request->file('video_file')->store('videos', 'public');

        return back()->with('success', 'Video uploaded successfully!');
    }
}