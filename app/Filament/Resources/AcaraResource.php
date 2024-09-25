<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Acara;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;


use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AcaraResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AcaraResource\RelationManagers;

class AcaraResource extends Resource
{
    protected static ?string $model = Acara::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nama_acara'),
                        TextInput::make('deskripsi'),
                        
                        FileUpload::make('gambar')
                        ->image() 
                        ->required(), 
                        DatePicker::make('tgl_mulai'),
                        DatePicker::make('tgl_akhir'),
                        DatePicker::make('tgl_ditutup'),
                        DatePicker::make('tgl_early'),
                        TextInput::make('lokasi'),
                        Select::make('status')
                        ->options([
                            'aktif' => 'Aktif',
                            'tidak_aktif' => 'Tidak Aktif',
                        ]),
                        TextInput::make('harga_early'),
                        TextInput::make('harga_reguler'),
                        TextInput::make('wa_link'),
                    ]),
                    Card::make([
                        Repeater::make('materis')
                            ->relationship('materis') // Relasi ke model ListMateri
                            ->schema([
                                TextInput::make('materi')
                                    ->label('Materi')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->createItemButtonLabel('Tambah Materi') // Teks tombol tambah materi baru
                            ->label('Materi Acara'),
                    ])->label('Daftar Materi'),
                    Card::make([
                        Repeater::make('fasilitas')
                            ->relationship('fasilitas') // Relasi ke model ListMateri
                            ->schema([
                                TextInput::make('fasilitas')
                                    ->label('Fasilitas')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->createItemButtonLabel('Tambah Fasilitas Acara') // Teks tombol tambah materi baru
                            ->label('Fasilitas'),
                    ])->label('Fasilitas Acara'),
            ]);
    }

    public static function table(Table $table): Table
    {
 
        return $table
            ->columns([
                TextColumn::make('nama_acara')
                ->label('Nama Acara')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->limit(50), // Membatasi jumlah karakter yang ditampilkan
            ImageColumn::make('gambar')->disk('public'),

            

            TextColumn::make('tgl_mulai')
                ->label('Tanggal Mulai')
                ->sortable()
            ->date('d-m-Y'), // Format tanggal

            TextColumn::make('tgl_akhir')
                ->label('Tanggal Akhir')
                ->sortable()
                ->date('d-m-Y'), // Format tanggal

            TextColumn::make('tgl_ditutup')
                ->label('Tanggal Ditutup')
                ->sortable()
                ->date('d-m-Y'), // Format tanggal

            TextColumn::make('tgl_early')
                ->label('Tanggal Early Bird')
                ->sortable()
                ->date('d-m-Y'), // Format tanggal

            TextColumn::make('lokasi')
                ->label('Lokasi')
                ->sortable()
                ->searchable(),

            SelectColumn::make('status')
                ->label('Status')
                ->options([
                    'aktif' => 'Aktif',
                    'tidak_aktif' => 'Tidak Aktif',
                ])
                ->sortable(),

            TextColumn::make('harga_early')
                ->label('Harga Early Bird')
                ->sortable()
                ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.')),

            TextColumn::make('harga_reguler')
                ->label('Harga Reguler')
                ->sortable()
                ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.')),

            TextColumn::make('wa_link')
                ->label('WhatsApp Link')
                ->sortable()
                ->limit(30),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAcaras::route('/'),
            'create' => Pages\CreateAcara::route('/create'),
            'edit' => Pages\EditAcara::route('/{record}/edit'),
        ];
    }    
}
