<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\HandlesUploads;
use Inertia\Inertia;

class BannerController extends Controller
{
    use HandlesUploads;
    public function index()
    {
        return Inertia::render(
            'Admin/Banners/Index',
            [
                'banners' =>
                    Banner::latest()->get(),
            ]
        );
    }

    public function store(
        Request $request
    ) {

        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $this->saveToPublic($request->file('image'), 'banners');
        }

        Banner::create([
            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'image' =>
                $image,

            'button_text' =>
                $request->button_text,

            'button_link' =>
                $request->button_link,

            'is_active' => true,
        ]);

        return back();
    }

    public function destroy(Banner $banner)
{
    if ($banner->image) {
        Storage::disk('public')->delete(
            $banner->image
        );
    }

    $banner->delete();

    return back()->with(
        'success',
        'Banner berhasil dihapus'
    );
}

public function toggle(Banner $banner)
{
    $banner->update([
        'is_active' => ! $banner->is_active
    ]);

    return back()->with(
        'success',
        'Status banner berhasil diubah'
    );
}
}