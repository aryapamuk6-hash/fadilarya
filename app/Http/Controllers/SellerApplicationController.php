<?php

namespace App\Http\Controllers;

use App\Models\SellerApplication;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SellerApplicationController extends Controller
{
    public function create()
    {
        $existing = SellerApplication::where(
            'user_id',
            auth()->id()
        )->latest()->first();

        return Inertia::render(
            'Seller/ApplicationForm',
            [
                'application' => $existing,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => ['required', 'max:255'],
            'description' => ['required'],
            'phone' => ['required', 'max:30'],

            'id_card' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

            'selfie' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);

        $idCard = $request
            ->file('id_card')
            ->store(
                'seller/id-cards',
                'public'
            );

        $selfie = $request
            ->file('selfie')
            ->store(
                'seller/selfies',
                'public'
            );

       SellerApplication::create([
    'user_id' => auth()->id(),
    'store_name' => $request->store_name,
    'description' => $request->description,
    'phone' => $request->phone,
    'id_card' => $idCard,
    'selfie' => $selfie,
    'seller_status' => 'pending',
]);

        auth()->user()->update([
            'seller_status' => 'pending',   
        ]);

        User::where('role', 'admin')->each(function (User $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Seller Baru',
                'message' => auth()->user()->name . ' mengirim pengajuan seller yang membutuhkan persetujuan.',
                'type' => 'seller_application',
            ]);
        });

        return redirect()
            ->route('seller.apply')
            ->with(
                'success',
                'Pengajuan seller berhasil dikirim'
            );
    }
}