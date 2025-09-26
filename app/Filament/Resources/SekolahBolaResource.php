<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahBolaResource\Pages;
use App\Filament\Resources\SekolahBolaResource\RelationManagers\PemainBolasRelationManager;
use App\Models\SekolahBola;
use App\Models\KuotaSekolah;
use App\Services\WablasService;
use App\Helpers\WhatsAppTemplates;
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

                // Kolom kuota
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
                        $totalPemain = $record->pemainBolas->count();
                        
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
                Tables\Actions\Action::make('send_whatsapp')
                    ->label('Kirim WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Pesan WhatsApp')
                    ->modalDescription('Kirim link pendaftaran ke SSB via WhatsApp')
                    ->modalSubmitActionLabel('Kirim Sekarang')
                    ->action(function ($record) {
                        $wablasService = app(WablasService::class);
                        $userLink = url("/user/{$record->user_token}");
                        
                        $message = WhatsAppTemplates::ssbRegistrationLink(
                            $record->nama,
                            $userLink,
                            $record->pic
                        );

                        $result = $wablasService->sendMessage($record->telepon, $message);

                        if ($result['success']) {
                            Notification::make()
                                ->title('WhatsApp berhasil dikirim!')
                                ->body("Pesan telah dikirim ke {$record->nama}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Gagal mengirim WhatsApp')
                                ->body('Error: ' . ($result['error'] ?? 'Unknown error'))
                                ->danger()
                                ->send();
                        }
                    }),
                    Tables\Actions\Action::make('send_quota_reminder')
                        ->label('Reminder Kuota')
                        ->icon('heroicon-o-bell')
                        ->color('warning')
                        ->visible(fn ($record) => $record->kuotaSekolah !== null)
                        ->requiresConfirmation()
                        ->modalHeading('Kirim Reminder Kuota')
                        ->action(function ($record) {
                            $kuota = $record->kuotaSekolah;
                            if (!$kuota) return;

                            $totalKuota = $kuota->kuota_7_8 + $kuota->kuota_9_10 + $kuota->kuota_11_12;
                            $totalPemain = $record->pemainBolas->count();
                            $sisaKuota = $totalKuota - $totalPemain;

                            if ($sisaKuota <= 0) {
                                // Kirim pesan kuota penuh
                                $message = WhatsAppTemplates::quotaFull($record->nama);
                            } else {
                                // Kirim reminder kuota
                                $userLink = url("/user/{$record->user_token}");
                                $message = WhatsAppTemplates::quotaReminder($record->nama, $sisaKuota, $userLink);
                            }

                            $wablasService = app(WablasService::class);
                            $result = $wablasService->sendMessage($record->telepon, $message);

                            if ($result['success']) {
                                Notification::make()
                                    ->title('Reminder berhasil dikirim!')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Gagal mengirim reminder')
                                    ->body($result['error'] ?? 'Unknown error')
                                    ->danger()
                                    ->send();
                            }
                        }),

                // Action untuk melihat detail kuota (dipindah dari kelola kuota)
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
                    ->modalDescription('Mengirim link pendaftaran ke semua SSB yang dipilih via WhatsApp')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        $wablasService = app(WablasService::class);
                        $successCount = 0;
                        $failedCount = 0;
                        $errors = [];

                        foreach ($records as $record) {
                            $userLink = url("/user/{$record->user_token}");
                            
                            $message = WhatsAppTemplates::ssbRegistrationLink(
                                $record->nama,
                                $userLink,
                                $record->pic
                            );

                            $result = $wablasService->sendMessage($record->telepon, $message);

                            if ($result['success']) {
                                $successCount++;
                            } else {
                                $failedCount++;
                                $errors[] = "{$record->nama}: " . ($result['error'] ?? 'Unknown error');
                            }

                            // Delay untuk menghindari rate limiting
                            sleep(1);
                        }

                        if ($successCount > 0) {
                            Notification::make()
                                ->title("WhatsApp Berhasil Dikirim!")
                                ->body("Berhasil: {$successCount}, Gagal: {$failedCount}")
                                ->success()
                                ->send();
                        }

                        if ($failedCount > 0) {
                            Notification::make()
                                ->title("Ada Pesan yang Gagal Dikirim")
                                ->body(implode("\n", array_slice($errors, 0, 3)) . ($failedCount > 3 ? "\n..." : ''))
                                ->warning()
                                ->send();
                        }
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
                                    Column::make('user_token')->heading('User Token'),
                                    Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                    Column::make('user_link')
                                        ->heading('User Link')
                                        ->formatStateUsing(fn ($state, $record) => url("/user/{$record->user_token}")),
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
                                Column::make('user_token')->heading('User Token'),
                                Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                Column::make('user_link')
                                    ->heading('User Link')
                                    ->formatStateUsing(fn ($state, $record) => url("/user/{$record->user_token}")),
                                Column::make('created_at')
                                    ->heading('Tanggal Dibuat')
                                    ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i')),
                            ])
                    ])
                    ->label('Export Semua Data')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down'),
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