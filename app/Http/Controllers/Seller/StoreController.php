<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function edit()
    {
        return Inertia::render(
            'Seller/Store/Edit',
            [
                'store' => auth()->user()->store,
            ]
        );
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;

        $request->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'address' => ['nullable'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('logo')) {

            if ($store->logo) {
                Storage::disk('public')->delete(
                    $store->logo
                );
            }

            $store->logo = $request
                ->file('logo')
                ->store(
                    'stores',
                    'public'
                );
        }

        if ($request->hasFile('banner')) {

            if ($store->banner) {
                Storage::disk('public')->delete(
                    $store->banner
                );
            }

            $store->banner = $request
                ->file('banner')
                ->store(
                    'stores',
                    'public'
                );
        }

        $store->name = $request->name;
        $store->description = $request->description;
        $store->address = $request->address;

        $store->save();

        return back()->with(
            'success',
            'Profil toko berhasil diperbarui'
        );
    }
}