<?php

use Illuminate\Support\Facades\Route;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PublicController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Public routes untuk rally
Route::get('/password', [PublicController::class, 'passwordForm'])->name('public.password');
Route::post('/password', [PublicController::class, 'checkPassword'])->name('public.password.check');

Route::middleware(['web', 'check.public.password'])->group(function () {
    Route::get('/', [PublicController::class, 'dashboard'])->name('public.dashboard');
    Route::get('/leaderboard-realtime', [PublicController::class, 'leaderboard'])->name('public.leaderboard');
    Route::get('/transaksi', [PublicController::class, 'formTransaksi'])->name('public.transaksi');
    Route::post('/transaksi', [PublicController::class, 'processTransaksi'])->name('public.transaksi.process');
    Route::get('/soal-by-peserta', [PublicController::class, 'getSoalByPesertaGet'])->name('public.soal.by.peserta');
    Route::get('/mutasi', [PublicController::class, 'mutasi'])->name('public.mutasi');
    Route::post('/mutasi', [PublicController::class, 'mutasi'])->name('public.mutasi.search');
});

Route::get('/leaderboards', function () {
    $peserta = Peserta::orderByDesc('saldo')->get();
    return view('leaderboard', compact('peserta'));
});

Route::get('/leaderboards/data', function () {
    $peserta = Peserta::orderByDesc('saldo')->get();
    return response()->json([
        'peserta' => $peserta->map(function($p) {
            return [
                'nama_tim' => $p->nama_tim,
                'smp_asal' => $p->smp_asal,
                'anggota_1' => $p->anggota_1,
                'anggota_2' => $p->anggota_2,
                'anggota_3' => $p->anggota_3,
                'saldo' => number_format($p->saldo, 0, ',', '.'),
                'saldo_raw' => $p->saldo
            ];
        })->toArray()
    ]);
});

Route::get('/admin/peserta/{peserta}/export-pdf', [\App\Http\Controllers\ExportController::class, 'export'])->name('peserta.export');

Route::get('/admin/peserta/{peserta}/export-pdf', [ExportController::class, 'export'])->name('peserta.export');


Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('email', $googleUser->getEmail())->first();

    if (! $user) {
        // Jika tidak ditemukan, tolak akses
        abort(403, 'Akun Anda belum terdaftar sebagai pengguna.');
    }

    Auth::login($user);

    return redirect('/admin');
});

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/admin/laporan/ringkasan', [\App\Http\Controllers\LaporanController::class, 'exportRingkasan'])->name('laporan.ringkasan');
