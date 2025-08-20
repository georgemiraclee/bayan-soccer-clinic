<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahBolaResource\Pages;
use App\Models\SekolahBola;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SekolahBolaResource extends Resource
{
    protected static ?string $model = SekolahBola::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Sekolah Bola';
    protected static ?string $pluralLabel = 'Daftar Sekolah Bola';
    protected static ?string $modelLabel = 'Sekolah Bola';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Sekolah Bola')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Sekolah Bola')
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

                Tables\Columns\TextColumn::make('pemain_bola_count')
                    ->label('Jumlah Pemain')
                    ->counts('pemainBola')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user_link')
                    ->label('User Link')
                    ->getStateUsing(fn ($record) => url("/user/{$record->id}"))
                    ->url(fn ($record) => url("/user/{$record->id}"))
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
                                ->withFilename(fn () => 'data-sekolah-bola-' . date('Y-m-d'))
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                                ->withColumns([
                                    Column::make('nama')->heading('Nama Sekolah Bola'),
                                    Column::make('pic')->heading('PIC'),
                                    Column::make('email')->heading('Email'),
                                    Column::make('telepon')->heading('Nomor Telepon'),
                                    Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                    Column::make('user_link')
                                        ->heading('User Link')
                                        ->formatStateUsing(fn ($state, $record) => url("/user/{$record->id}")),
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
                                Column::make('nama')->heading('Nama Sekolah Bola'),
                                Column::make('pic')->heading('PIC'),
                                Column::make('email')->heading('Email'),
                                Column::make('telepon')->heading('Nomor Telepon'),
                                Column::make('pemain_bola_count')->heading('Jumlah Pemain'),
                                Column::make('user_link')
                                    ->heading('User Link')
                                    ->formatStateUsing(fn ($state, $record) => url("/user/{$record->id}")),
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
            // nanti bisa ditambahkan RelationManager ke PemainBola
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSekolahBolas::route('/'),
            'create' => Pages\CreateSekolahBola::route('/create'),
            'edit' => Pages\EditSekolahBola::route('/{record}/edit'),
        ];
    }
}