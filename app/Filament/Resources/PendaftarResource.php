<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Acara;
use App\Models\Pendaftar;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PendaftarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PendaftarResource\RelationManagers;

class PendaftarResource extends Resource
{
    protected static ?string $model = Pendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 // Kolom Nama Acara (relasi ke Acara)
                 TextColumn::make('acara.nama_acara')
                 ->label('Nama Acara')->searchable(),

             // Kolom Deskripsi Acara (relasi ke Acara)
             TextColumn::make('acara.deskripsi')
                 ->label('Deskripsi Acara')->searchable(),

             // Kolom Nama User (relasi ke User)
             TextColumn::make('user.name')
                 ->label('Nama User')->searchable(),

             // Kolom Nomor Telepon User (relasi ke User)
             TextColumn::make('user.wa')
                 ->label('Nomor Telepon')->searchable(),

             // Kolom Status
             TextColumn::make('status')
                 ->label('Status')->searchable(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPendaftars::route('/'),
            'create' => Pages\CreatePendaftar::route('/create'),
            'edit' => Pages\EditPendaftar::route('/{record}/edit'),
        ];
    }    
}
