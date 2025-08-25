<?php

namespace App\Filament\Resources\SekolahBolaResource\Pages;
use App\Filament\Resources\SekolahBolaResource\RelationManagers\PemainBolasRelationManager;
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
                        
                  Forms\Components\Section::make('Daftar Pemain Saat Ini')
                    ->schema([
                        Forms\Components\Placeholder::make('pemain_stats')
                            ->label('')
                            ->content(function () {
                                $record = $this->getRecord();
                                
                                $pemain7_8 = $record->pemainBolas->where('umur_kategori', '7-8')->count();
                                $pemain9_10 = $record->pemainBolas->where('umur_kategori', '9-10')->count();
                                $pemain11_12 = $record->pemainBolas->where('umur_kategori', '11-12')->count();
                                $total = $record->pemainBolas->count();
                                
                            }),
                            
                      Forms\Components\Placeholder::make('pemain_table')
                        ->label('')
                        ->content(function () {
                            $record = $this->getRecord();
                            
                            // Urutkan pemain berdasarkan kategori umur dengan urutan khusus
                            $pemainBolas = $record->pemainBolas->sortBy(function ($pemain) {
                                // Urutan kategori: 7-8, 9-10, 11-12
                                $kategoriOrder = [
                                    '7-8' => 1,
                                    '9-10' => 2, 
                                    '11-12' => 3
                                ];
                                
                                $kategoriPriority = $kategoriOrder[$pemain->umur_kategori] ?? 999;
                                
                                // Gabungkan priority kategori dengan nama untuk sorting
                                return sprintf('%03d_%s', $kategoriPriority, $pemain->nama);
                            });
                            
                            if ($pemainBolas->count() === 0) {
                                return new \Illuminate\Support\HtmlString("
                                    <div class='text-center text-gray-500 dark:text-gray-400 py-8'>
                                        <div class='text-lg'>Belum ada pemain terdaftar</div>
                                    </div>
                                ");
                            }
                            
                            $tableRows = '';
                            foreach ($pemainBolas as $index => $pemain) {
                                $umur = $pemain->umur;
                                $umur_kategori = $pemain->umur_kategori;
                                $badgeColor = '';
                                
                                switch ($umur_kategori) {
                                    case '7-8':
                                        $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        break;
                                    case '9-10':
                                        $badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        break;
                                    case '11-12':
                                        $badgeColor = 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200';
                                        break;
                                    default:
                                        $badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                        break;
                                }
                                
                                $no = $index + 1;
                                $nama = e($pemain->nama);
                                $umurDisplay = $umur . ' tahun';
                                
                                $tableRows .= "
                                    <tr class='border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors'>
                                        <td class='px-4 py-3 text-center font-medium text-gray-900 dark:text-gray-100'>{$no}</td>
                                        <td class='px-4 py-3 font-medium text-gray-900 dark:text-gray-100'>{$nama}</td>
                                        <td class='px-4 py-3 text-center text-gray-900 dark:text-gray-100'>{$umurDisplay}</td>
                                        <td class='px-4 py-3 text-center'>
                                            <span class='px-2 py-1 text-xs font-semibold rounded-full {$badgeColor}'>
                                                {$umur_kategori} Tahun
                                            </span>
                                        </td>
                                    </tr>
                                ";
                            }
                            
                            return new \Illuminate\Support\HtmlString("
                                <div class='overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900'>
                                    <div class='max-h-96 overflow-y-auto'>
                                        <table class='w-full'>
                                            <thead class='bg-gray-50 dark:bg-gray-800 sticky top-0'>
                                                <tr>
                                                    <th class='px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider'>No</th>
                                                    <th class='px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider'>Nama Pemain</th>
                                                    <th class='px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider'>Umur</th>
                                                    <th class='px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider'>Kategori</th>
                                                </tr>
                                            </thead>
                                            <tbody class='bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700'>
                                                {$tableRows}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class='mt-2 text-sm text-gray-500 dark:text-gray-400 text-center'>
                                    Menampilkan {$pemainBolas->count()} pemain (diurutkan berdasarkan kategori: 7-8, 9-10, 11-12 tahun)
                                </div>
                            ");
                        }),
                    ])
                    ->collapsible()
                    ->collapsed(false),
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

                // Section untuk Kuota (jika ada)
                Infolists\Components\Section::make('Kuota SSB')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('kuotaSekolah.kuota_7_8')
                                    ->label('Kuota 7-8 Tahun')
                                    ->badge()
                                    ->color('success'),
                                    
                                Infolists\Components\TextEntry::make('kuotaSekolah.kuota_9_10')
                                    ->label('Kuota 9-10 Tahun')
                                    ->badge()
                                    ->color('info'),
                                    
                                Infolists\Components\TextEntry::make('kuotaSekolah.kuota_11_12')
                                    ->label('Kuota 11-12 Tahun')
                                    ->badge()
                                    ->color('warning'),
                            ]),
                        
                        Infolists\Components\TextEntry::make('kuotaSekolah.notes')
                            ->label('Catatan')
                            ->placeholder('Tidak ada catatan')
                            ->columnSpanFull(),
                            
                        Infolists\Components\TextEntry::make('kuotaSekolah.updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d M Y H:i')
                            ->badge()
                            ->color('gray'),
                    ])
                    ->visible(fn ($record) => $record->kuotaSekolah !== null),

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

        
                // Pesan jika belum ada pemain
                Infolists\Components\Section::make('Belum Ada Pemain')
                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        Infolists\Components\TextEntry::make('no_players_message')
                            ->hiddenLabel()
                            ->formatStateUsing(fn () => 'ℹ️ Belum ada pemain yang terdaftar di SSB ini.')
                            ->badge()
                            ->color('info'),
                    ])
                    ->visible(fn ($record) => $record->pemainBolas->count() === 0),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            PemainBolasRelationManager::class,
        ];
    }
}