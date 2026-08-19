<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerApplication;
use App\Models\Store;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SellerApplicationController extends Controller
{
    public function index()
    {
        $applications = SellerApplication::with('user')
            ->latest()
            ->get();

        return Inertia::render(
            'Admin/SellerApplications',
            [
                'applications' => $applications
            ]
        );
    }

    public function approve(
        SellerApplication $sellerApplication
    )
    {
        $sellerApplication->update([
    'seller_status' => 'approved',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
]);

        $user = $sellerApplication->user;

        $user->update([
            'role' => 'seller',
            'seller_status' => 'approved',
        ]);

        Store::create([
            'user_id' => $user->id,
            'name' => $sellerApplication->store_name,
            'slug' => Str::slug(
                $sellerApplication->store_name
            ) . '-' . $user->id,
            'description' => $sellerApplication->description,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Pengajuan Seller Disetujui',
            'message' => 'Pengajuan seller Anda telah disetujui admin.',
            'type' => 'seller_application',
        ]);

        return back()->with(
            'success',
            'Seller berhasil diapprove'
        );
    }

    public function reject(
        SellerApplication $sellerApplication
    )
    {
        $sellerApplication->update([
    'seller_status' => 'rejected',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
]);

        $sellerApplication->user->update([
            'seller_status' => 'rejected',
        ]);

        Notification::create([
            'user_id' => $sellerApplication->user_id,
            'title' => 'Pengajuan Seller Ditolak',
            'message' => 'Pengajuan seller Anda belum disetujui admin.',
            'type' => 'seller_application',
        ]);

        return back()->with(
            'success',
            'Pengajuan ditolak'
        );
    }
}