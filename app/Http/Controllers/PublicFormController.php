<?php

namespace App\Http\Controllers;

use App\Models\PesertaLari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PublicFormController extends Controller
{
    /**
     * Tampilkan form pendaftaran event lari
     */
    public function index()
    {
        return view('public-form');
    }

    /**
     * Proses pendaftaran event lari
     */
    public function store(Request $request)
    {
        // Validasi data peserta
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kategori_lari' => 'required|string|max:100',
            'email' => 'required|email|unique:peserta_laris,email',
            'telepon' => 'required|string|max:20',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'kategori_lari.required' => 'Kategori lari harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain',
            'telepon.required' => 'Nomor telepon harus diisi'
        ]);

        try {
            DB::beginTransaction();

            // Generate nomor BIB unik
            $nomorBib = $this->generateNomorBib();
            
            // Generate token unik untuk QR Code
            $qrToken = Str::random(32);

            // Simpan data peserta
            $peserta = PesertaLari::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'kategori_lari' => $validated['kategori_lari'],
                'email' => $validated['email'],
                'telepon' => $validated['telepon'],
                'nomor_bib' => $nomorBib,
                'qr_token' => $qrToken,
                'status' => 'terdaftar'
            ]);

            // Generate QR Code data
            $qrData = json_encode([
                'id' => $peserta->id,
                'nama' => $peserta->nama_lengkap,
                'nomor_bib' => $nomorBib,
                'kategori' => $peserta->kategori_lari,
                'token' => $qrToken
            ]);

            // Generate QR Code URL
            $qrCodeUrl = $this->generateQRCodeURL($qrData);

            // Simpan QR Code
            $qrCodePath = $this->downloadAndSaveQRCode($qrCodeUrl, $peserta->id);
            
            // Update path QR Code di database
            if ($qrCodePath) {
                $peserta->update(['qr_code_path' => $qrCodePath]);
            }

            DB::commit();

            // Kirim WhatsApp via Wablas
            $whatsappResult = $this->sendWhatsAppNotification($peserta, $qrCodeUrl);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Anda berhasil! Terima kasih sudah mendaftar pada event lari ini. Detail pendaftaran sudah kami kirimkan melalui WhatsApp.',
                'data' => [
                    'id' => $peserta->id,
                    'nama' => $peserta->nama_lengkap,
                    'nomor_bib' => $nomorBib,
                    'kategori' => $peserta->kategori_lari,
                    'qr_code_url' => $qrCodeUrl,
                    'whatsapp_sent' => $whatsappResult['success'] ?? false,
                    'whatsapp_message' => $whatsappResult['message'] ?? 'Unknown status'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $validated ?? []
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Generate nomor BIB unik
     */
    private function generateNomorBib()
    {
        do {
            $nomorBib = 'BIB-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PesertaLari::where('nomor_bib', $nomorBib)->exists());

        return $nomorBib;
    }

    /**
     * Generate QR Code URL menggunakan service eksternal
     */
    private function generateQRCodeURL($data)
    {
        $encodedData = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&data={$encodedData}";
    }

    /**
     * Download dan simpan QR Code dari URL eksternal
     */
    private function downloadAndSaveQRCode($qrCodeUrl, $pesertaId)
    {
        try {
            // Download QR Code dari URL
            $response = Http::timeout(10)->get($qrCodeUrl);
            
            if ($response->successful()) {
                // Buat direktori jika belum ada
                $qrDir = storage_path('app/public/qr-codes');
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0755, true);
                }

                // Simpan file QR Code
                $qrCodePath = 'qr-codes/' . $pesertaId . '.png';
                $fullPath = storage_path('app/public/' . $qrCodePath);
                
                file_put_contents($fullPath, $response->body());
                
                Log::info('QR Code saved successfully', [
                    'peserta_id' => $pesertaId,
                    'path' => $qrCodePath
                ]);
                
                return $qrCodePath;
            }
        } catch (\Exception $e) {
            Log::error('QR Code download error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Kirim notifikasi WhatsApp via Wablas
     */
    private function sendWhatsAppNotification($peserta, $qrCodeUrl)
    {
        try {
            // Ambil konfigurasi dari config/services.php
            $wablasApiUrl = config('services.wablas.url');
            $wablasToken = config('services.wablas.token');
            $wablasImageUrl = config('services.wablas.image_url');
            
            // Validasi konfigurasi
            if (empty($wablasToken) || empty($wablasApiUrl)) {
                Log::error('Wablas configuration missing', [
                    'api_url' => $wablasApiUrl,
                    'token_exists' => !empty($wablasToken)
                ]);
                return ['success' => false, 'message' => 'Wablas configuration not found'];
            }

            // Format nomor telepon
            $phoneNumber = $this->formatPhoneNumber($peserta->telepon);
            
            Log::info('Sending WhatsApp to: ' . $phoneNumber, [
                'peserta_id' => $peserta->id,
                'original_phone' => $peserta->telepon
            ]);

            // Siapkan pesan
            $message = $this->prepareWhatsAppMessage($peserta, $qrCodeUrl);

            // Kirim pesan teks terlebih dahulu
            $textResponse = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $wablasToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($wablasApiUrl, [
                    'phone' => $phoneNumber,
                    'message' => $message,
                    'isGroup' => false
                ]);

            Log::info('WhatsApp text message response', [
                'peserta_id' => $peserta->id,
                'status_code' => $textResponse->status(),
                'response_body' => $textResponse->body()
            ]);

            // Kirim QR Code sebagai gambar
            $imageResponse = null;
            if ($wablasImageUrl) {
                try {
                    $imageResponse = Http::timeout(30)
                        ->withHeaders([
                            'Authorization' => $wablasToken,
                            'Content-Type' => 'application/json',
                        ])
                        ->post($wablasImageUrl, [
                            'phone' => $phoneNumber,
                            'image' => $qrCodeUrl,
                            'caption' => "🎫 QR Code Pendaftaran Event Lari\n📝 Nama: {$peserta->nama_lengkap}\n🏃‍♂️ BIB: {$peserta->nomor_bib}\n🏆 Kategori: {$peserta->kategori_lari}"
                        ]);

                    Log::info('WhatsApp image message response', [
                        'peserta_id' => $peserta->id,
                        'status_code' => $imageResponse->status(),
                        'response_body' => $imageResponse->body()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send WhatsApp image: ' . $e->getMessage(), [
                        'peserta_id' => $peserta->id
                    ]);
                }
            }

            // Evaluasi hasil pengiriman
            if ($textResponse->successful()) {
                return [
                    'success' => true,
                    'message' => 'WhatsApp message sent successfully',
                    'text_response' => $textResponse->json(),
                    'image_response' => $imageResponse ? $imageResponse->json() : null
                ];
            } else {
                Log::error('WhatsApp sending failed', [
                    'peserta_id' => $peserta->id,
                    'text_status' => $textResponse->status(),
                    'text_body' => $textResponse->body(),
                    'image_status' => $imageResponse ? $imageResponse->status() : null
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send WhatsApp message: ' . $textResponse->body()
                ];
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp notification error: ' . $e->getMessage(), [
                'peserta_id' => $peserta->id,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor telepon untuk WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Hilangkan semua karakter selain angka
        $phoneNumber = preg_replace('/[^0-9]/', '', $phone);
        
        // Format nomor telepon Indonesia
        if (substr($phoneNumber, 0, 2) === '62') {
            // Sudah dalam format internasional
            return $phoneNumber;
        } elseif (substr($phoneNumber, 0, 1) === '0') {
            // Format lokal dengan 0 di depan, ganti dengan 62
            return '62' . substr($phoneNumber, 1);
        } else {
            // Asumsi nomor tanpa kode negara dan tanpa 0
            return '62' . $phoneNumber;
        }
    }

    /**
     * Siapkan pesan WhatsApp
     */
    private function prepareWhatsAppMessage($peserta, $qrCodeUrl)
    {
        $message = "🎉 BAYAN RUN 2025 - PENDAFTARAN BERHASIL! 🎉\n\n";
        $message .= "Halo {$peserta->nama_lengkap}! 👋\n\n";
        $message .= "Terima kasih telah mendaftar di Event Coaching Clinic Bayan Run 2025! 🏃‍♂️✨\n\n";
        $message .= "📋 DETAIL PENDAFTARAN:\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "👤 Nama: {$peserta->nama_lengkap}\n";
        $message .= "🏆 Kategori: {$peserta->kategori_lari}\n";
        $message .= "📧 Email: {$peserta->email}\n";
        $message .= "📱 No. HP: {$peserta->telepon}\n";
        $message .= "🎫 Nomor BIB: {$peserta->nomor_bib}\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "📱 QR Code Anda akan dikirim sebagai gambar berikutnya sebagai bukti registrasi.\n\n";
        $message .= "⚠️ PENTING:\n";
        $message .= "• Simpan QR Code ini dengan baik\n";
        $message .= "• Tunjukkan QR Code saat check-in event\n";
        $message .= "• Datang 30 menit sebelum acara dimulai\n\n";
        $message .= "🎯 Info lebih lanjut akan kami kirimkan menjelang event.\n\n";
        $message .= "Sampai jumpa di garis start! 🏃‍♂️🏃‍♀️\n\n";
        $message .= "Salam Olahraga,\n";
        $message .= "Tim Bayan Run 2025 🏃‍♂️✨";

        return $message;
    }

    /**
     * Test WhatsApp connection
     */
    public function testWhatsApp(Request $request)
    {
        $phone = $request->input('phone', '6285377640809'); // Default test number
        
        try {
            $wablasApiUrl = config('services.wablas.url');
            $wablasToken = config('services.wablas.token');
            
            if (empty($wablasToken)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wablas token not configured'
                ]);
            }

            $testMessage = "🧪 Test pesan dari sistem Bayan Run 2025\n\n";
            $testMessage .= "Jika Anda menerima pesan ini, maka konfigurasi WhatsApp sudah benar! ✅\n\n";
            $testMessage .= "Waktu test: " . now()->format('d/m/Y H:i:s');

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $wablasToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($wablasApiUrl, [
                    'phone' => $this->formatPhoneNumber($phone),
                    'message' => $testMessage
                ]);

            Log::info('WhatsApp test result', [
                'phone' => $phone,
                'status_code' => $response->status(),
                'response_body' => $response->body()
            ]);

            return response()->json([
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response' => $response->json(),
                'message' => $response->successful() 
                    ? 'Test message sent successfully!' 
                    : 'Failed to send test message'
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp test error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ... (sisanya tetap sama seperti kode asli)

    /**
     * Tampilkan data peserta (untuk admin)
     */
    public function showRegistrations()
    {
        $pesertaLaris = PesertaLari::orderBy('created_at', 'desc')->get();
        return view('registrations.index', compact('pesertaLaris'));
    }

    /**
     * Detail pendaftaran peserta
     */
    public function showDetail($id)
    {
        $peserta = PesertaLari::findOrFail($id);
        return view('registrations.detail', compact('peserta'));
    }

    /**
     * Verifikasi QR Code
     */
    public function verifyQR($token)
    {
        $peserta = PesertaLari::where('qr_token', $token)->first();
        
        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $peserta->id,
                'nama' => $peserta->nama_lengkap,
                'nomor_bib' => $peserta->nomor_bib,
                'kategori' => $peserta->kategori_lari,
                'status' => $peserta->status,
                'waktu_daftar' => $peserta->created_at->format('d/m/Y H:i')
            ]
        ]);
    }

    /**
     * Export data ke Excel/CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $pesertaLaris = PesertaLari::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'message' => 'Export functionality untuk peserta event lari',
            'total_data' => $pesertaLaris->count()
        ]);
    }

    /**
     * API untuk mendapatkan statistik pendaftaran
     */
    public function getStats()
    {
        $totalPeserta = PesertaLari::count();
        
        $pesertaPerKategori = PesertaLari::select('kategori_lari', DB::raw('count(*) as total'))
            ->groupBy('kategori_lari')
            ->get();

        $pesertaTerbaru = PesertaLari::latest()->take(10)->get();

        return response()->json([
            'total_peserta' => $totalPeserta,
            'peserta_per_kategori' => $pesertaPerKategori,
            'peserta_terbaru' => $pesertaTerbaru
        ]);
    }

    /**
     * Regenerate QR Code untuk peserta tertentu
     */
    public function regenerateQR($id)
    {
        try {
            $peserta = PesertaLari::findOrFail($id);
            
            $qrData = json_encode([
                'id' => $peserta->id,
                'nama' => $peserta->nama_lengkap,
                'nomor_bib' => $peserta->nomor_bib,
                'kategori' => $peserta->kategori_lari,
                'token' => $peserta->qr_token
            ]);

            $qrCodeUrl = $this->generateQRCodeURL($qrData);
            $qrCodePath = $this->downloadAndSaveQRCode($qrCodeUrl, $peserta->id);
            
            $peserta->update(['qr_code_path' => $qrCodePath]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil di-generate ulang',
                'qr_code_url' => $qrCodeUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal me-regenerate QR Code: ' . $e->getMessage()
            ], 500);
        }
    }
}