<?php

namespace App\Http\Controllers;

use App\Models\TopUp;
use App\Http\Controllers\Traits\HandlesUploads;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TopUpController extends Controller
{
    use HandlesUploads;

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

        $proof = $this->saveToPublic($request->file('proof'), 'topups');

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