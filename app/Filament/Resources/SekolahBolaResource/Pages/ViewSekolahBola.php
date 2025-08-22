<?php

namespace App\Filament\Resources\SekolahBolaResource\Pages;

use App\Filament\Resources\SekolahBolaResource;
use App\Models\KuotaSekolah;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

class ViewSekolahBola extends ViewRecord
{
    protected static string $resource = SekolahBolaResource::class;

    public function getTitle(): string
    {
        return "Detail {$this->getRecord()->nama}";
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action untuk mengelola kuota
            Actions\Action::make('kelola_kuota')
                ->label('Kelola Kuota')
                ->icon('heroicon-m-adjustments-horizontal')
                ->color('warning')
                ->modalHeading('Kelola Kuota - ' . $this->getRecord()->nama)
                ->form([
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
                ])
                ->fillForm(function () {
                    $kuota = $this->getRecord()->kuotaSekolah;
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
                })
                ->action(function (array $data) {
                    // Update atau create kuota
                    KuotaSekolah::updateOrCreate(
                        ['sekolah_bola_id' => $this->getRecord()->id],
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
                })
                ->modalWidth(MaxWidth::FourExtraLarge),

            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Informasi Sekolah Bola
                Infolists\Components\Section::make('Informasi SSB')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('nama')
                                    ->label('Nama SSB')
                                    ->weight('bold'),
                                    
                                Infolists\Components\TextEntry::make('pic')
                                    ->label('PIC'),
                                    
                                Infolists\Components\TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),
                                    
                                Infolists\Components\TextEntry::make('telepon')
                                    ->label('Telepon'),
                                    
                                Infolists\Components\TextEntry::make('user_link')
                                    ->label('Link Pendaftaran')
                                    ->formatStateUsing(fn () => 'Buka Link')
                                    ->url(fn ($record) => url("/user/{$record->user_token}"))
                                    ->openUrlInNewTab()
                                    ->badge()
                                    ->color('primary'),
                                    
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Dibuat')
                                    ->dateTime('d M Y H:i'),
                            ])
                    ]),

                // Pesan jika belum ada kuota
                Infolists\Components\Section::make('Kuota Belum Diatur')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_quota_message')
                            ->hiddenLabel()
                            ->formatStateUsing(fn () => '⚠️ Kuota untuk SSB ini belum diatur. Silakan gunakan tombol "Kelola Kuota" untuk mengatur kuota.')
                            ->badge()
                            ->color('warning'),
                    ])
                    ->visible(fn ($record) => $record->kuotaSekolah === null),
            ]);
    }
}