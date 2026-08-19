<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return inertia(
            'Admin/Announcements/Index',
            [
                'announcements' =>
                    Announcement::latest()->get(),
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            'Pengumuman berhasil dibuat'
        );
    }
}