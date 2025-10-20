<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahBolaResource\Pages;
use App\Filament\Resources\SekolahBolaResource\RelationManagers\PemainBolasRelationManager;
use App\Models\SekolahBola;
use App\Models\KuotaSekolah;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Notifications\Notification;

class SekolahBolaResource extends Resource
{
    protected static ?string $model = SekolahBola::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'List SSB';
    protected static ?string $pluralLabel = 'List SSB';
    protected static ?string $modelLabel = 'List SSB';

    /**
     * Generate WhatsApp redirect link with pre-filled message
     */
    private static function generateWhatsAppLink(string $phoneNumber, string $message): string
    {
        // Clean phone number (remove +, spaces, dashes)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not exists (assume Indonesia +62)
        if (!str_starts_with($cleanPhone, '62')) {
            // Remove leading 0 if exists
            $cleanPhone = ltrim($cleanPhone, '0');
            $cleanPhone = '62' . $cleanPhone;
        }
        
        // URL encode the message
        $encodedMessage = urlencode($message);
        
        // Return WhatsApp API link
        return "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    }

    /**
     * Generate registration message template
     */
    private static function getRegistrationMessage(string $namaSSB, string $userLink, string $picName = '', $kuotaSekolah = null): string
    {
        $hour = date('H');
        $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        
        if ($picName) {
            $greeting .= " Bapak/Ibu {$picName}";
        }

        // Build kategori section based on kuota
        $kategoriText = " *Kategori yang Tersedia:*\n";
        
        if ($kuotaSekolah) {
            $availableKategori = [];
            
            if ($kuotaSekolah->kuota_7_8 > 0) {
                $availableKategori[] = "• 7-8 Tahun (Kuota: {$kuotaSekolah->kuota_7_8} pemain)";
            }
            
            if ($kuotaSekolah->kuota_9_10 > 0) {
                $availableKategori[] = "• 9-10 Tahun (Kuota: {$kuotaSekolah->kuota_9_10} pemain)";
            }
            
            if ($kuotaSekolah->kuota_11_12 > 0) {
                $availableKategori[] = "• 11-12 Tahun (Kuota: {$kuotaSekolah->kuota_11_12} pemain)";
            }
            
            if (empty($availableKategori)) {
                $kategoriText .= "• Belum ada kuota yang diset\n";
            } else {
                $kategoriText .= implode("\n", $availableKategori) . "\n";
            }
        } else {
            // Jika kuota belum diset, tampilkan kategori default
            $kategoriText .= "• 7-8 Tahun\n" .
                           "• 9-10 Tahun\n" .
                           "• 11-12 Tahun\n";
        }

    return " *REGISTRASI ULANG PEMAIN SSB - BAYAN SOCCER CLINIC*\n\n" .
        "{$greeting}\n\n" .
        "Sehubungan dengan penyesuaian data peserta, kami meminta Tim *{$namaSSB}* untuk melakukan *registrasi ulang pemain* sesuai dengan kuota yang telah ditentukan.\n\n" .
        " *Link Registrasi Ulang Khusus SSB Anda:*\n" .
        "{$userLink}\n\n" .
        $kategoriText . "\n" .
        " *Penting:*\n" .
        "- Gunakan link di atas untuk melakukan registrasi ulang pemain sesuai kuota yang tersedia\n" .
        "- Pastikan data pemain diisi dengan lengkap dan benar\n" .
        "- Registrasi ulang diperlukan untuk validasi data peserta yang akan mengikuti kegiatan\n\n" .
        "Jika ada pertanyaan, silakan hubungi panitia.\n\n" .
        "Terima kasih atas kerja samanya!\n" .
        "*Panitia Bayan Soccer Clinic*";
        }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama SSB')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('pic')
                    ->label('PIC')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('telepon')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('user_token')
                    ->label('User Token')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => $record !== null)
                    ->helperText('Token ini dibuat otomatis dan tidak dapat diubah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withOptimalRelations() // Gunakan scope dari model
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama SSB')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pic')
                    ->label('PIC')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Nomor Telepon'),
                    
                Tables\Columns\TextColumn::make('pemain_bolas_count')
                    ->label('Jumlah Pemain')
                    ->counts('pemainBolas')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('jumlah_pemain_7_8')
                    ->label('Pemain 7–8')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->jumlah_pemain_7_8 ?? 0),

                Tables\Columns\TextColumn::make('jumlah_pemain_9_10')
                    ->label('Pemain 9–10')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->jumlah_pemain_9_10 ?? 0),

                Tables\Columns\TextColumn::make('jumlah_pemain_11_12')
                    ->label('Pemain 11–12')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->jumlah_pemain_11_12 ?? 0),

                Tables\Columns\TextColumn::make('kuota_info')
                    ->label('Kuota (7-8 | 9-10 | 11-12)')
                    ->getStateUsing(function ($record) {
                        $kuota = $record->kuotaSekolah;
                        if (!$kuota) {
                            return 'Belum diset';
                        }
                        return "{$kuota->kuota_7_8} | {$kuota->kuota_9_10} | {$kuota->kuota_11_12}";
                    })
                    ->badge()
                    ->color(fn ($record) => $record->kuotaSekolah ? 'success' : 'gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('kuota_status')
                    ->label('Status Kuota')
                    ->getStateUsing(function ($record) {
                        $kuota = $record->kuotaSekolah;
                        if (!$kuota) {
                            return 'Tidak ada kuota';
                        }
                        $totalKuota = $kuota->kuota_7_8 + $kuota->kuota_9_10 + $kuota->kuota_11_12;
                        $totalPemain = $record->pemain_bolas_count ?? 0;
                        
                        if ($totalPemain >= $totalKuota) {
                            return 'Penuh';
                        } elseif ($totalPemain >= ($totalKuota * 0.8)) {
                            return 'Hampir Penuh';
                        } else {
                            return 'Tersedia';
                        }
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'Penuh' => 'danger',
                        'Hampir Penuh' => 'warning',
                        'Tersedia' => 'success',
                        default => 'gray'
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('user_link')
                    ->label('User Link')
                    ->getStateUsing(fn ($record) => url("/user/{$record->user_token}"))
                    ->url(fn ($record) => url("/user/{$record->user_token}"))
                    ->openUrlInNewTab()
                    ->copyable()
                    ->copyMessage('Link berhasil disalin!')
                    ->copyMessageDuration(1500)
                    ->badge()
                    ->color('primary')
                    ->icon('heroicon-m-link')
                    ->formatStateUsing(fn ($state) => 'Buka Link'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kuota_status')
                    ->label('Status Kuota')
                    ->options([
                        'ada' => 'Sudah Ada Kuota',
                        'kosong' => 'Belum Ada Kuota',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'ada',
                            fn (Builder $query): Builder => $query->whereHas('kuotaSekolah'),
                        )->when(
                            $data['value'] === 'kosong',
                            fn (Builder $query): Builder => $query->whereDoesntHave('kuotaSekolah'),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                // WhatsApp Action - Opens WhatsApp Web/App with pre-filled message
                Tables\Actions\Action::make('send_whatsapp')
                    ->label('Kirim WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function ($record) {
                        $userLink = url("/user/{$record->user_token}");
                        $message = self::getRegistrationMessage($record->nama, $userLink, $record->pic, $record->kuotaSekolah);
                        return self::generateWhatsAppLink($record->telepon, $message);
                    })
                    ->openUrlInNewTab()
                    ->tooltip('Buka WhatsApp dengan pesan otomatis'),

                Tables\Actions\Action::make('detail_kuota')
                    ->label('Detail Kuota')
                    ->icon('heroicon-m-information-circle')
                    ->color('info')
                    ->visible(fn ($record) => $record->kuotaSekolah !== null)
                    ->modalHeading(fn ($record) => 'Detail Kuota - ' . $record->nama)
                    ->modalContent(function ($record) {
                        $kuota = $record->kuotaSekolah;
                        if (!$kuota) {
                            return 'Kuota belum diset untuk SSB ini.';
                        }

                        // Gunakan data yang sudah di-load dari withCount
                        $pemain_7_8 = $record->jumlah_pemain_7_8 ?? 0;
                        $pemain_9_10 = $record->jumlah_pemain_9_10 ?? 0;
                        $pemain_11_12 = $record->jumlah_pemain_11_12 ?? 0;
                        
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
                        
                        if ($kuota->notes) {
                            $html .= '<div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">';
                            $html .= '<h4 class="font-semibold mb-2">Catatan:</h4>';
                            $html .= '<p class="text-gray-700 dark:text-gray-300">' . e($kuota->notes) . '</p>';
                            $html .= '</div>';
                        }
                        
                        $html .= '<div class="text-xs text-gray-500 border-t pt-2">';
                        $html .= 'Terakhir diupdate: ' . $kuota->updated_at->format('d M Y H:i');
                        if ($kuota->updatedBy) {
                            $html .= ' oleh ' . $kuota->updatedBy->name;
                        }
                        $html .= '</div>';
                        
                        $html .= '</div>';
                        
                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->modalWidth('4xl'),
            ])
            ->bulkActions([
                BulkAction::make('bulk_send_whatsapp')
                    ->label('Kirim WhatsApp Massal')
                    ->icon('heroicon-o-megaphone')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim WhatsApp ke Semua SSB Terpilih')
                    ->modalDescription('Akan membuka tab WhatsApp untuk setiap SSB yang dipilih')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        $links = [];
                        
                        foreach ($records as $record) {
                            $userLink = url("/user/{$record->user_token}");
                            $message = self::getRegistrationMessage($record->nama, $userLink, $record->pic, $record->kuotaSekolah);
                            $links[] = self::generateWhatsAppLink($record->telepon, $message);
                        }

                        // Store links in session to open them via JavaScript
                        session(['whatsapp_links' => $links]);

                        Notification::make()
                            ->title('Siap Mengirim WhatsApp')
                            ->body("Akan membuka {$records->count()} tab WhatsApp. Pastikan popup tidak diblokir browser.")
                            ->success()
                            ->send();

                        // Return JavaScript to open links
                        return redirect()->back()->with('openWhatsAppLinks', true);
                    }),
                    
                Tables\Actions\BulkActionGroup::make([
                   ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'data-sekolah-bola-' . date('Y-m-d'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                                ->withColumns([
                                    Column::make('nama')->heading('Nama SSB'),
                                    Column::make('pic')->heading('PIC'),
                                    Column::make('email')->heading('Email'),
                                    Column::make('telepon')->heading('Nomor Telepon'),
                                    Column::make('user_token')
                                        ->heading('User Link')
                                        ->formatStateUsing(fn ($state) => url("/user/{$state}")),
                                    Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                    Column::make('created_at')
                                        ->heading('Tanggal Dibuat')
                                        ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i')),
                                ])
                        ])
                        ->label('Export Excel'),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
              \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'semua-data-sekolah-bola-' . date('Y-m-d'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                            ->withColumns([
                                Column::make('nama')->heading('Nama SSB'),
                                Column::make('pic')->heading('PIC'),
                                Column::make('email')->heading('Email'),
                                Column::make('telepon')->heading('Nomor Telepon'),
                                Column::make('user_token')
                                    ->heading('User Link')
                                    ->formatStateUsing(fn ($state) => url("/user/{$state}")),
                                Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                Column::make('created_at')
                                    ->heading('Tanggal Dibuat')
                                    ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i')),
                            ])
                    ])
                    ->label('Export Semua Data')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PemainBolasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSekolahBolas::route('/'),
            'create' => Pages\CreateSekolahBola::route('/create'),
            'edit' => Pages\EditSekolahBola::route('/{record}/edit'),
            'view' => Pages\ViewSekolahBola::route('/{record}'),
        ];
    }
}