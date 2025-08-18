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
                    ->label('Email'),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Nomor Telepon'),

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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
