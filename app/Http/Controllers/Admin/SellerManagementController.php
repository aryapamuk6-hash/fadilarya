<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;

class SellerManagementController extends Controller
{
    public function index()
    {
        $sellers = User::whereIn(
    'seller_status',
    [
        'approved',
        'suspended',
    ]
)
        ->with('store')
        ->latest()
        ->get();

        return inertia(
            'Admin/Sellers/Index',
            [
                'sellers' => $sellers,
            ]
        );
    }

   public function warning(User $user)
{
    if ($user->role !== 'seller') {
        abort(404);
    }

    $user->increment('warning_count');

    $user->refresh();

    if ($user->warning_count >= 3) {

        $user->update([
            'role' => 'user',
            'seller_status' => 'suspended',
            'is_suspended' => true,
            'suspension_reason' => 'Melebihi batas warning',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Akun Seller Ditangguhkan',
            'message' => 'Akun seller Anda ditangguhkan karena melebihi batas warning.',
        ]);

    } else {

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Seller',
            'message' => 'Admin memberikan peringatan kepada akun seller Anda.',
        ]);

    }

    return back()->with(
        'success',
        'Warning berhasil diberikan'
    );


        Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Seller',
            'message' => 'Admin memberikan peringatan kepada akun seller Anda.',
        ]);

        return back()->with(
            'success',
            'Warning berhasil diberikan'
        );
    }

    public function suspend(User $user)
{
    $user->update([
        'role' => 'user',
        'seller_status' => 'suspended',
        'is_suspended' => true,
        'suspension_reason' => 'Melanggar aturan marketplace',
    ]);

    Notification::create([
        'user_id' => $user->id,
        'title' => 'Akun Seller Ditangguhkan',
        'message' => 'Hak seller Anda telah dicabut sementara oleh admin.',
    ]);

    return back()->with(
        'success',
        'Seller berhasil disuspend'
    );
}
   public function unsuspend(User $user)
{
    $user->update([
        'role' => 'seller',
        'seller_status' => 'approved',
        'is_suspended' => false,
        'suspension_reason' => null,
    ]);

    Notification::create([
        'user_id' => $user->id,
        'title' => 'Akun Seller Aktif Kembali',
        'message' => 'Hak seller Anda telah dipulihkan oleh admin.',
    ]);

    return back()->with(
        'success',
        'Seller berhasil diaktifkan kembali'
    );
}
}