<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KuotaSekolahResource\Pages;
use App\Models\KuotaSekolah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class KuotaSekolahResource extends Resource
{
    protected static ?string $model = KuotaSekolah::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Kelola Kuota SSB';
    protected static ?string $pluralLabel = 'Kelola Kuota SSB';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sekolah_bola_id')
                    ->label('SSB')
                    ->relationship('sekolahBola', 'nama')
                    ->required(),

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

                // Field updated_by akan diisi otomatis, jadi disembunyikan dari form
                Forms\Components\Hidden::make('updated_by'),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->placeholder('Catatan khusus tentang kuota ini...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sekolahBola.nama')
                    ->label('Nama SSB')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('kuota_7_8')
                    ->label('Kuota 7-8')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kuota_9_10')
                    ->label('Kuota 9-10')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kuota_11_12')
                    ->label('Kuota 11-12')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_kuota')
                    ->label('Total Kuota')
                    ->getStateUsing(fn ($record) => $record->kuota_7_8 + $record->kuota_9_10 + $record->kuota_11_12)
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('pemain_count')
                    ->label('Pemain Terdaftar')
                    ->getStateUsing(fn ($record) => $record->sekolahBola->pemainBolas->count())
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Diupdate Oleh')
                    ->placeholder('System')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make()
                                ->fromTable()
                                ->withFilename(fn () => 'kuota-sekolah-bola-' . date('Y-m-d'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                                ->withColumns([
                                    Column::make('sekolahBola.nama')->heading('Nama SSB'),
                                    Column::make('kuota_7_8')->heading('Kuota 7-8 Tahun'),
                                    Column::make('kuota_9_10')->heading('Kuota 9-10 Tahun'),
                                    Column::make('kuota_11_12')->heading('Kuota 11-12 Tahun'),
                                    Column::make('total_kuota')
                                        ->heading('Total Kuota')
                                        ->formatStateUsing(fn ($state, $record) => $record->kuota_7_8 + $record->kuota_9_10 + $record->kuota_11_12),
                                    Column::make('pemain_count')
                                        ->heading('Pemain Terdaftar')
                                        ->formatStateUsing(fn ($state, $record) => $record->sekolahBola->pemainBolas->count()),
                                    Column::make('updatedBy.name')
                                        ->heading('Diupdate Oleh')
                                        ->formatStateUsing(fn ($state) => $state ?: 'System'),
                                    Column::make('notes')->heading('Catatan'),
                                    Column::make('updated_at')
                                        ->heading('Terakhir Diupdate')
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
                            ->withFilename(fn () => 'semua-kuota-sekolah-bola-' . date('Y-m-d'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                            ->withColumns([
                                Column::make('sekolahBola.nama')->heading('Nama SSB'),
                                Column::make('sekolahBola.pic')->heading('PIC'),
                                Column::make('sekolahBola.email')->heading('Email'),
                                Column::make('sekolahBola.telepon')->heading('Nomor Telepon'),
                                Column::make('kuota_7_8')->heading('Kuota 7-8 Tahun'),
                                Column::make('kuota_9_10')->heading('Kuota 9-10 Tahun'),
                                Column::make('kuota_11_12')->heading('Kuota 11-12 Tahun'),
                                Column::make('total_kuota')
                                    ->heading('Total Kuota')
                                    ->formatStateUsing(fn ($state, $record) => $record->kuota_7_8 + $record->kuota_9_10 + $record->kuota_11_12),
                                Column::make('pemain_count')
                                    ->heading('Pemain Terdaftar')
                                    ->formatStateUsing(fn ($state, $record) => $record->sekolahBola->pemainBolas->count()),
                                Column::make('status_kuota')
                                    ->heading('Status Kuota')
                                    ->formatStateUsing(function ($state, $record) {
                                        $totalKuota = $record->kuota_7_8 + $record->kuota_9_10 + $record->kuota_11_12;
                                        $totalPemain = $record->sekolahBola->pemainBolas->count();
                                        
                                        if ($totalPemain >= $totalKuota) {
                                            return 'Penuh';
                                        } elseif ($totalPemain >= ($totalKuota * 0.8)) {
                                            return 'Hampir Penuh';
                                        } else {
                                            return 'Tersedia';
                                        }
                                    }),
                                Column::make('persentase_terisi')
                                    ->heading('Persentase Terisi (%)')
                                    ->formatStateUsing(function ($state, $record) {
                                        $totalKuota = $record->kuota_7_8 + $record->kuota_9_10 + $record->kuota_11_12;
                                        $totalPemain = $record->sekolahBola->pemainBolas->count();
                                        
                                        return $totalKuota > 0 ? round(($totalPemain / $totalKuota) * 100, 2) : 0;
                                    }),
                                Column::make('updatedBy.name')
                                    ->heading('Diupdate Oleh')
                                    ->formatStateUsing(fn ($state) => $state ?: 'System'),
                                Column::make('notes')->heading('Catatan'),
                                Column::make('updated_at')
                                    ->heading('Terakhir Diupdate')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKuotaSekolahs::route('/'),
            'create' => Pages\CreateKuotaSekolah::route('/create'),
            'edit' => Pages\EditKuotaSekolah::route('/{record}/edit'),
        ];
    }
}