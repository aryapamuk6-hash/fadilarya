<?php

namespace App\Http\Controllers;

use App\Models\TopUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TopUpController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'TopUp/Index',
            [
                'topUps' => TopUp::where(
                    'user_id',
                    auth()->id()
                )
                ->latest()
                ->get(),
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:10000',
            ],
            'proof' => [
                'required',
                'image',
                'max:2048',
            ],
        ]);

        $proof = $request
            ->file('proof')
            ->store(
                'topups',
                'public'
            );

        TopUp::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'proof' => $proof,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Top up berhasil diajukan'
        );
    }
}