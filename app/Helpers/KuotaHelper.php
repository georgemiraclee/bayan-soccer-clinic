<?php

namespace App\Helpers;

use App\Models\KuotaSekolah;
use Filament\Forms;
use Filament\Notifications\Notification;

class KuotaHelper
{
    /**
     * Get form schema for kuota management
     */
    public static function getKuotaFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('kuota_7_8')
                        ->label('Kuota 7-8 Tahun')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->default(0),

                    Forms\Components\TextInput::make('kuota_9_10')
                        ->label('Kuota 9-10 Tahun')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->default(0),

                    Forms\Components\TextInput::make('kuota_11_12')
                        ->label('Kuota 11-12 Tahun')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                ]),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->placeholder('Catatan khusus tentang kuota ini...')
                ->rows(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * Get form data for existing kuota
     */
    public static function getKuotaFormData($record): array
    {
        $kuota = $record->kuotaSekolah;
        if ($kuota) {
            return [
                'kuota_7_8' => $kuota->kuota_7_8,
                'kuota_9_10' => $kuota->kuota_9_10,
                'kuota_11_12' => $kuota->kuota_11_12,
                'notes' => $kuota->notes,
            ];
        }
        
        return [
            'kuota_7_8' => 0,
            'kuota_9_10' => 0,
            'kuota_11_12' => 0,
            'notes' => '',
        ];
    }

    /**
     * Update or create kuota
     */
    public static function updateKuota($record, array $data): void
    {
        KuotaSekolah::updateOrCreate(
            ['sekolah_bola_id' => $record->id],
            [
                'kuota_7_8' => $data['kuota_7_8'],
                'kuota_9_10' => $data['kuota_9_10'],
                'kuota_11_12' => $data['kuota_11_12'],
                'notes' => $data['notes'],
                'updated_by' => auth()->id(),
            ]
        );

        Notification::make()
            ->title('Kuota berhasil diupdate')
            ->success()
            ->send();
    }

    /**
     * Calculate quota statistics
     */
    public static function getQuotaStatistics($record): array
    {
        $kuota = $record->kuotaSekolah;
        $pemainBolas = $record->pemainBolas;

        // Hitung pemain per kategori
        $pemain_7_8 = $pemainBolas->where('umur_kategori', '7-8')->count();
        $pemain_9_10 = $pemainBolas->where('umur_kategori', '9-10')->count();
        $pemain_11_12 = $pemainBolas->where('umur_kategori', '11-12')->count();

        $statistics = [
            'pemain_7_8' => $pemain_7_8,
            'pemain_9_10' => $pemain_9_10,
            'pemain_11_12' => $pemain_11_12,
            'total_pemain' => $pemainBolas->count(),
        ];

        // Hitung persentase jika ada kuota
        $persentase = [];
        if ($kuota) {
            $persentase = [
                '7_8' => $kuota->kuota_7_8 > 0 ? round(($pemain_7_8 / $kuota->kuota_7_8) * 100) : 0,
                '9_10' => $kuota->kuota_9_10 > 0 ? round(($pemain_9_10 / $kuota->kuota_9_10) * 100) : 0,
                '11_12' => $kuota->kuota_11_12 > 0 ? round(($pemain_11_12 / $kuota->kuota_11_12) * 100) : 0,
            ];

            $totalKuota = $kuota->kuota_7_8 + $kuota->kuota_9_10 + $kuota->kuota_11_12;
            $totalPemain = $pemain_7_8 + $pemain_9_10 + $pemain_11_12;
            $persentase['total'] = $totalKuota > 0 ? round(($totalPemain / $totalKuota) * 100) : 0;
        }

        return [
            'statistics' => $statistics,
            'persentase' => $persentase,
        ];
    }

    /**
     * Get quota status for display
     */
    public static function getQuotaStatus($record): string
    {
        $kuota = $record->kuotaSekolah;
        if (!$kuota) {
            return 'Tidak ada kuota';
        }

        $totalKuota = $kuota->kuota_7_8 + $kuota->kuota_9_10 + $kuota->kuota_11_12;
        $totalPemain = $record->pemainBolas->count();
        
        if ($totalPemain >= $totalKuota) {
            return 'Penuh';
        } elseif ($totalPemain >= ($totalKuota * 0.8)) {
            return 'Hampir Penuh';
        } else {
            return 'Tersedia';
        }
    }

    /**
     * Get quota info display
     */
    public static function getQuotaInfo($record): string
    {
        $kuota = $record->kuotaSekolah;
        if (!$kuota) {
            return 'Belum diset';
        }
        return "{$kuota->kuota_7_8} | {$kuota->kuota_9_10} | {$kuota->kuota_11_12}";
    }

    /**
     * Generate quota detail HTML for modal
     */
    public static function generateQuotaDetailHtml($record): \Illuminate\Support\HtmlString
    {
        $kuota = $record->kuotaSekolah;
        if (!$kuota) {
            return new \Illuminate\Support\HtmlString('Kuota belum diset untuk sekolah ini.');
        }

        // Hitung pemain per kategori
        $pemain = $record->pemainBolas;
        $pemain_7_8 = $pemain->where('umur_kategori', '7-8')->count();
        $pemain_9_10 = $pemain->where('umur_kategori', '9-10')->count();
        $pemain_11_12 = $pemain->where('umur_kategori', '11-12')->count();
        
        $html = '<div class="space-y-6">';
        $html .= '<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">';
        $html .= '<h3 class="font-semibold text-lg mb-4">Ringkasan Kuota vs Pemain Terdaftar</h3>';
        $html .= '<div class="grid grid-cols-3 gap-4">';
        
        // Kategori 7-8
        $persentase_7_8 = $kuota->kuota_7_8 > 0 ? round(($pemain_7_8 / $kuota->kuota_7_8) * 100) : 0;
        $html .= '<div class="text-center p-3 bg-white dark:bg-gray-900 rounded border">';
        $html .= '<div class="text-sm font-medium text-gray-500 dark:text-gray-400">7-8 Tahun</div>';
        $html .= '<div class="text-2xl font-bold text-blue-600">' . $pemain_7_8 . ' / ' . $kuota->kuota_7_8 . '</div>';
        $html .= '<div class="text-sm text-gray-500">(' . $persentase_7_8 . '%)</div>';
        $html .= '</div>';
        
        // Kategori 9-10
        $persentase_9_10 = $kuota->kuota_9_10 > 0 ? round(($pemain_9_10 / $kuota->kuota_9_10) * 100) : 0;
        $html .= '<div class="text-center p-3 bg-white dark:bg-gray-900 rounded border">';
        $html .= '<div class="text-sm font-medium text-gray-500 dark:text-gray-400">9-10 Tahun</div>';
        $html .= '<div class="text-2xl font-bold text-yellow-600">' . $pemain_9_10 . ' / ' . $kuota->kuota_9_10 . '</div>';
        $html .= '<div class="text-sm text-gray-500">(' . $persentase_9_10 . '%)</div>';
        $html .= '</div>';
        
        // Kategori 11-12
        $persentase_11_12 = $kuota->kuota_11_12 > 0 ? round(($pemain_11_12 / $kuota->kuota_11_12) * 100) : 0;
        $html .= '<div class="text-center p-3 bg-white dark:bg-gray-900 rounded border">';
        $html .= '<div class="text-sm font-medium text-gray-500 dark:text-gray-400">11-12 Tahun</div>';
        $html .= '<div class="text-2xl font-bold text-green-600">' . $pemain_11_12 . ' / ' . $kuota->kuota_11_12 . '</div>';
        $html .= '<div class="text-sm text-gray-500">(' . $persentase_11_12 . '%)</div>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        // Total
        $totalKuota = $kuota->kuota_7_8 + $kuota->kuota_9_10 + $kuota->kuota_11_12;
        $totalPemain = $pemain_7_8 + $pemain_9_10 + $pemain_11_12;
        $persentaseTotal = $totalKuota > 0 ? round(($totalPemain / $totalKuota) * 100) : 0;
        
        $html .= '<div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">';
        $html .= '<h4 class="font-semibold text-lg mb-2">Total Keseluruhan</h4>';
        $html .= '<div class="text-3xl font-bold text-blue-700 dark:text-blue-300">' . $totalPemain . ' / ' . $totalKuota . '</div>';
        $html .= '<div class="text-sm text-blue-600 dark:text-blue-400">Terisi ' . $persentaseTotal . '%</div>';
        $html .= '</div>';
        
        // Catatan
        if ($kuota->notes) {
            $html .= '<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">';
            $html .= '<h4 class="font-semibold mb-2">Catatan:</h4>';
            $html .= '<p class="text-gray-700 dark:text-gray-300">' . e($kuota->notes) . '</p>';
            $html .= '</div>';
        }
        
        // Info update
        $html .= '<div class="text-xs text-gray-500 border-t pt-2">';
        $html .= 'Terakhir diupdate: ' . $kuota->updated_at->format('d M Y H:i');
        if ($kuota->updatedBy) {
            $html .= ' oleh ' . $kuota->updatedBy->name;
        }
        $html .= '</div>';
        
        $html .= '</div>';
        
        return new \Illuminate\Support\HtmlString($html);
    }
}