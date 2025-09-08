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

            if ($jenis === 'beli') {
                // Untuk beli, tampilkan soal yang belum pernah dibeli
                $soalsBeli = Transaksi::where('peserta_id', $peserta_id)
                    ->where('keterangan', 'Beli')
                    ->pluck('kode_soal');
                
                $soals = Soal::whereNotIn('kode_soal', $soalsBeli)->get();
            } else {
                // Untuk jual, tampilkan soal yang sudah dibeli tapi belum dijual
                $soalsBeli = Transaksi::where('peserta_id', $peserta_id)
                    ->where('keterangan', 'Beli')
                    ->pluck('kode_soal');
                
                $soalsJual = Transaksi::where('peserta_id', $peserta_id)
                    ->whereIn('keterangan', ['Jual - Benar', 'Jual - Salah'])
                    ->pluck('kode_soal');
                
                $soals = Soal::whereIn('kode_soal', $soalsBeli)
                    ->whereNotIn('kode_soal', $soalsJual)
                    ->get();
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
            
            // Cek apakah peserta sudah membeli soal (untuk jual)
            if ($request->jenis === 'jual') {
                $sudahBeli = Transaksi::where('peserta_id', $request->peserta_id)
                    ->where('kode_soal', $request->kode_soal)
                    ->where('keterangan', 'Beli')
                    ->exists();
                
                if (!$sudahBeli) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peserta harus membeli soal terlebih dahulu sebelum menjual!'
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
            }
            
            $keterangan = '';
            $feedback = '';
            $debet = 0;
            $kredit = 0;

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
                
                $keterangan = 'Beli';
                $kredit = $soal->harga_beli; // Kurangi saldo
                
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
                // Jual - cek jawaban berdasarkan tipe soal
                $jawabanBenar = false;
                
                if ($soal->tipe_soal === 'Pilihan Ganda') {
                    // Untuk pilihan ganda, bandingkan langsung
                    $jawabanBenar = strtoupper(trim($request->jawaban)) === strtoupper(trim($soal->kunci_jawaban));
                    
                } elseif ($soal->tipe_soal === 'Pilihan Ganda Kompleks') {
                    // Untuk pilihan ganda kompleks, bandingkan array
                    $jawabanUser = explode(',', strtoupper(str_replace(' ', '', $request->jawaban)));
                    $kunciJawaban = json_decode($soal->kunci_jawaban, true);
                    
                    if (is_array($kunciJawaban)) {
                        sort($jawabanUser);
                        sort($kunciJawaban);
                        $jawabanBenar = $jawabanUser === $kunciJawaban;
                    }
                    
                } elseif ($soal->tipe_soal === 'Essai') {
                    // Untuk essai, bandingkan dengan case-insensitive dan trim whitespace
                    $jawabanUser = strtolower(trim($request->jawaban));
                    $kunciJawaban = strtolower(trim($soal->kunci_jawaban));
                    
                    // Bisa ditambahkan logika fuzzy matching atau similarity check
                    $jawabanBenar = $jawabanUser === $kunciJawaban;
                    
                    // Optional: tambahkan similarity check untuk essai
                    // $similarity = similar_text($jawabanUser, $kunciJawaban, $percent);
                    // $jawabanBenar = $percent >= 80; // 80% similarity threshold
                }
                
                if ($jawabanBenar) {
                    $keterangan = 'Jual - Benar';
                    $debet = $soal->harga_benar; // Tambah saldo
                    $feedback = "Jawaban kamu benar! Kamu dapat Rp" . number_format($soal->harga_benar, 0, ',', '.');
                    
                    // Update saldo peserta
                    $peserta->saldo += $soal->harga_benar;
                    $peserta->save();
                    
                } else {
                    $keterangan = 'Jual - Salah';
                    $debet = $soal->harga_salah; // Tambah saldo (lebih kecil)
                    $feedback = "Jawaban kamu salah. Kamu dapat Rp" . number_format($soal->harga_salah, 0, ',', '.');
                    
                    // Update saldo peserta
                    $peserta->saldo += $soal->harga_salah;
                    $peserta->save();
                }
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
                'sound' => $request->jenis === 'jual' && str_contains($keterangan, 'Benar') ? 'success' : 'fail'
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
}
