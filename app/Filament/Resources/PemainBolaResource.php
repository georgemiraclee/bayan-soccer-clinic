<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemainBolaResource\Pages;
use App\Models\PemainBola;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PemainBolaResource extends Resource
{
    protected static ?string $model = PemainBola::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pemain Bola';
    protected static ?string $pluralLabel = 'Daftar Pemain Bola';
    protected static ?string $modelLabel = 'Pemain Bola';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('sekolah_bola_id')
                ->relationship('sekolahBola', 'nama')
                ->label('Sekolah Bola')
                ->required(),

            Forms\Components\TextInput::make('nama')
                ->label('Nama Pemain')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('umur_kategori')
                ->label('Kategori Umur')
                ->options([
                    '7-8' => '7-8 Tahun',
                    '9-10' => '9-10 Tahun',
                    '11-12' => '11-12 Tahun',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pemain')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('umur_kategori')
                    ->label('Umur')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sekolahBola.nama')
                    ->label('Sekolah Bola')
                    ->sortable()
                    ->searchable(),
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
                  ExportBulkAction::make(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPemainBolas::route('/'),
            'create' => Pages\CreatePemainBola::route('/create'),
            'edit' => Pages\EditPemainBola::route('/{record}/edit'),
        ];
    }
}
