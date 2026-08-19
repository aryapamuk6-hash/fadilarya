<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\TopUp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TopUpController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/TopUps/Index', [
            'topUps' => TopUp::with('user')
                ->latest()
                ->get(),
        ]);
    }

    public function approve(TopUp $topUp)
    {
        if ($topUp->status !== 'pending') {
            return back();

        Notification::create([
    'user_id' => $topUp->user_id,
    'title' => 'Top Up Disetujui',
    'message' => 'Top up sebesar Rp ' .
        number_format($topUp->amount,0,',','.') .
        ' berhasil disetujui.',
    'type' => 'topup',
]);
        }

        DB::transaction(function () use ($topUp) {

            $topUp->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $topUp->user->increment(
                'balance',
                $topUp->amount
            );
        });

        return back()->with(
            'success',
            'Top Up berhasil disetujui'
        );
    }

    public function reject(TopUp $topUp)
    {
        if ($topUp->status !== 'pending') {
            return back();
        }

        DB::transaction(function () use ($topUp) {
            $topUp->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            Notification::create([
                'user_id' => $topUp->user_id,
                'title' => 'Top Up Ditolak',
                'message' => 'Silakan upload ulang bukti transfer.',
                'type' => 'topup',
            ]);
        });

        return back()->with(
            'success',
            'Top Up ditolak'
        );
    }

    public function destroy(TopUp $topUp)
    {
        if ($topUp->proof) {
            Storage::disk('public')->delete($topUp->proof);
        }

        $topUp->delete();

        return back()->with(
            'success',
            'Riwayat top up berhasil dihapus'
        );
    }
}