<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Soal;
use App\Models\Transaksi;

class PublicController extends Controller
{
    public function passwordForm()
    {
        return view('public.password');
    }

    public function checkPassword(Request $request)
    {
        $password = $request->input('password');
        $correctPassword = env('RALLY_PUBLIC_PASSWORD', 'shsc2025');

        if ($password === $correctPassword) {
            session(['public_auth' => true]);
            return redirect()->route('public.dashboard');
        }

        return back()->withErrors(['password' => 'Password salah!']);
    }

    public function dashboard()
    {
        return view('public.dashboard');
    }

    public function leaderboard()
    {
        $peserta = Peserta::orderByDesc('saldo')->get();
        return response()->json($peserta);
    }

    public function formTransaksi()
    {
        $pesertas = Peserta::all();
        $soals = Soal::all();
        return view('public.form-transaksi', compact('pesertas', 'soals'));
    }

    public function getSoalByPeserta(Request $request)
    {
        try {
            // Debug request
            \Log::info('getSoalByPeserta called', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            $peserta_id = $request->peserta_id;
            $jenis = $request->jenis;
            $peserta = Peserta::find($peserta_id);
            
            // Generate transaksi soal gratis jika belum ada
            $this->generateSoalGratisTransaksi($peserta_id);

            if ($jenis === 'beli') {
                // Cek berapa soal yang sedang di-hold (dibeli tapi belum dijual)
                $soalsBeli = Transaksi::where('peserta_id', $peserta_id)
                    ->where('keterangan', 'Beli')
                    ->pluck('kode_soal');
                
                $soalsJual = Transaksi::where('peserta_id', $peserta_id)
                    ->whereIn('keterangan', ['Jual - Benar', 'Jual - Salah'])
                    ->pluck('kode_soal');
                
                // Soal yang sedang di-hold
                $soalsHold = $soalsBeli->diff($soalsJual);
                
                // Cek batasan maksimal 3 soal
                if ($soalsHold->count() >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maksimal hold 3 soal. Jual soal yang sudah ada terlebih dahulu.',
                        'soals' => collect([])
                    ]);
                }
                
                // Untuk beli, tampilkan soal yang belum pernah dibeli
                $soals = Soal::whereNotIn('kode_soal', $soalsBeli)->get();
                
            } else {
                // Untuk jual, tampilkan soal yang sudah dibeli (termasuk soal gratis) tapi belum dijual
                $soalsBeli = Transaksi::where('peserta_id', $peserta_id)
                    ->whereIn('keterangan', ['Beli', 'Soal Gratis'])
                    ->pluck('kode_soal');
                
                $soalsJual = Transaksi::where('peserta_id', $peserta_id)
                    ->whereIn('keterangan', ['Jual - Benar', 'Jual - Salah'])
                    ->pluck('kode_soal');
                
                // Soal yang bisa dijual (sudah dibeli tapi belum dijual)
                $availableSoals = $soalsBeli->diff($soalsJual);
                
                $soals = Soal::whereIn('kode_soal', $availableSoals)->get();
            }

            \Log::info('getSoalByPeserta result', ['soals_count' => $soals->count()]);

            return response()->json([
                'success' => true,
                'soals' => $soals
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getSoalByPeserta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processTransaksi(Request $request)
    {
        try {
            $request->validate([
                'peserta_id' => 'required|exists:peserta,id',
                'kode_soal' => 'required|exists:soal,kode_soal',
                'jenis' => 'required|in:beli,jual',
                'jawaban' => 'required_if:jenis,jual'
            ]);

            $peserta = Peserta::find($request->peserta_id);
            $soal = Soal::where('kode_soal', $request->kode_soal)->first();
            
            // Cek apakah soal ini adalah soal gratis
            $isSoalGratis = in_array($request->kode_soal, $peserta->soal_gratis ?? []);
            
            if ($request->jenis === 'beli') {
                // Cek apakah sudah pernah beli soal ini
                $sudahBeli = Transaksi::where('peserta_id', $request->peserta_id)
                    ->where('kode_soal', $request->kode_soal)
                    ->where('keterangan', 'Beli')
                    ->exists();
                
                if ($sudahBeli) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal ini sudah pernah dibeli sebelumnya!'
                    ], 422);
                }
                
                // Cek batasan maksimal 3 soal hold
                $soalsBeli = Transaksi::where('peserta_id', $request->peserta_id)
                    ->where('keterangan', 'Beli')
                    ->pluck('kode_soal');
                
                $soalsJual = Transaksi::where('peserta_id', $request->peserta_id)
                    ->whereIn('keterangan', ['Jual - Benar', 'Jual - Salah'])
                    ->pluck('kode_soal');
                
                $soalsHold = $soalsBeli->diff($soalsJual);
                
                if ($soalsHold->count() >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maksimal hold 3 soal! Jual soal yang sudah ada terlebih dahulu.'
                    ], 422);
                }
                
                $keterangan = 'Beli';
                $debet = $soal->harga_beli;
                $kredit = 0;
                
                // Cek apakah saldo mencukupi
                if ($peserta->saldo < $soal->harga_beli) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo tidak mencukupi! Saldo Anda: Rp' . number_format($peserta->saldo, 0, ',', '.') . ', Harga soal: Rp' . number_format($soal->harga_beli, 0, ',', '.')
                    ], 422);
                }
                
                $feedback = "Berhasil membeli soal {$soal->kode_soal}. Saldo dikurangi Rp" . number_format($soal->harga_beli, 0, ',', '.');
                
                // Update saldo peserta
                $peserta->saldo -= $soal->harga_beli;
                $peserta->save();
                
            } else {
                // Validasi untuk jual
                // Cek apakah peserta sudah membeli soal (termasuk soal gratis)
                $sudahBeli = Transaksi::where('peserta_id', $request->peserta_id)
                    ->where('kode_soal', $request->kode_soal)
                    ->whereIn('keterangan', ['Beli', 'Soal Gratis'])
                    ->exists();
                
                if (!$sudahBeli) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peserta harus membeli soal atau mendapatkan soal gratis terlebih dahulu sebelum menjual!'
                    ], 422);
                }
                
                // Cek apakah sudah pernah jual soal ini
                $sudahJual = Transaksi::where('peserta_id', $request->peserta_id)
                    ->where('kode_soal', $request->kode_soal)
                    ->whereIn('keterangan', ['Jual - Benar', 'Jual - Salah'])
                    ->exists();
                
                if ($sudahJual) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Soal ini sudah pernah dijual sebelumnya!'
                    ], 422);
                }
                
                // Jual - cek jawaban berdasarkan tipe soal
                $jawabanBenar = false;
                
                if ($soal->tipe_soal === 'Pilihan Ganda') {
                    $jawabanBenar = strtoupper(trim($request->jawaban)) === strtoupper(trim($soal->kunci_jawaban));
                } elseif ($soal->tipe_soal === 'Pilihan Ganda Kompleks') {
                    $jawabanUser = is_array($request->jawaban) ? $request->jawaban : [$request->jawaban];
                    $jawabanSoal = is_array($soal->kunci_jawaban) ? $soal->kunci_jawaban : explode(',', $soal->kunci_jawaban);
                    
                    sort($jawabanUser);
                    sort($jawabanSoal);
                    
                    $jawabanBenar = $jawabanUser === $jawabanSoal;
                } elseif ($soal->tipe_soal === 'Essai') {
                    $jawabanBenar = stripos($request->jawaban, $soal->kunci_jawaban) !== false;
                }
                
                $debet = 0;
                if ($jawabanBenar) {
                    $keterangan = 'Jual - Benar';
                    $kredit = $soal->harga_benar;
                    $feedback = "Jawaban BENAR! Soal {$soal->kode_soal} terjual. Saldo bertambah Rp" . number_format($soal->harga_benar, 0, ',', '.');
                } else {
                    $keterangan = 'Jual - Salah';
                    $kredit = $soal->harga_salah;
                    $feedback = "Jawaban SALAH. Soal {$soal->kode_soal} terjual. Saldo bertambah Rp" . number_format($soal->harga_salah, 0, ',', '.');
                }
                
                // Update saldo peserta
                $peserta->saldo += $kredit;
                $peserta->save();
            }

            // Buat transaksi
            $transaksi = new Transaksi();
            $transaksi->peserta_id = $request->peserta_id;
            $transaksi->kode_soal = $request->kode_soal;
            $transaksi->keterangan = $keterangan;
            $transaksi->debet = $debet;
            $transaksi->kredit = $kredit;
            $transaksi->total_saldo = $peserta->fresh()->saldo;
            $transaksi->save();

            return response()->json([
                'success' => true,
                'feedback' => $feedback,
                'saldo_baru' => $peserta->fresh()->saldo,
                'is_soal_gratis' => $isSoalGratis
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in processTransaksi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function mutasi(Request $request)
    {
        if ($request->ajax()) {
            $peserta_id = $request->peserta_id;
            $transaksis = Transaksi::with('peserta')
                ->where('peserta_id', $peserta_id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json($transaksis);
        }

        $pesertas = Peserta::all();
        return view('public.mutasi', compact('pesertas'));
    }

    public function getSoalByPesertaGet(Request $request)
    {
        return $this->getSoalByPeserta($request);
    }
    
    /**
     * Generate otomatis transaksi "Soal Gratis" untuk soal gratis peserta
     */
    public function generateSoalGratisTransaksi($peserta_id)
    {
        $peserta = Peserta::find($peserta_id);
        if (!$peserta || !$peserta->soal_gratis) return;

        foreach ($peserta->soal_gratis as $kodeSoal) {
            // Cek apakah transaksi "Soal Gratis" sudah ada
            $sudahAda = Transaksi::where('peserta_id', $peserta_id)
                ->where('kode_soal', $kodeSoal)
                ->where('keterangan', 'Soal Gratis')
                ->exists();

            if (!$sudahAda) {
                // Buat transaksi "Soal Gratis" dengan harga 0
                Transaksi::create([
                    'peserta_id' => $peserta_id,
                    'kode_soal' => $kodeSoal,
                    'keterangan' => 'Soal Gratis',
                    'debet' => 0,
                    'kredit' => 0,
                    'total_saldo' => $peserta->saldo,
                ]);
            }
        }
    }
}
