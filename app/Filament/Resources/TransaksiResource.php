<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Peserta;
use App\Models\Soal;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;


class TransaksiResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Koord Juri','Panitia']);
    }

    protected static ?string $model = Transaksi::class;

    protected static ?string $pluralModelLabel = 'Transaksi';
    
    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('peserta_id')
                ->label('Peserta')
                ->options(Peserta::all()->pluck('kode_peserta', 'id'))
                ->searchable()
                ->required(),

            Select::make('keterangan')
                ->label('Keterangan')
                ->options([
                    'Beli' => 'Beli',
                    'Jual - Benar' => 'Jual - Benar',
                    'Jual - Salah' => 'Jual - Salah',
                    'Modal' => 'Modal',
                ])
                ->required(),

            Select::make('kode_soal')
                ->label('Kode Soal')
                ->options(Soal::all()->pluck('kode_soal', 'kode_soal'))
                ->searchable()
                ->nullable()
                ->hidden(fn (callable $get) => $get('keterangan') === 'Modal'),

            // Field untuk modal manual (hanya muncul jika keterangan = Modal)
            TextInput::make('modal_amount')
                ->label('Jumlah Modal')
                ->numeric()
                ->visible(fn (callable $get) => $get('keterangan') === 'Modal')
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if ($get('keterangan') === 'Modal') {
                        $set('kredit', $state);
                        $set('debet', 0);
                    }
                }),

            // Debet & Kredit akan diisi otomatis, tidak perlu manual (kecuali untuk Modal)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('peserta.kode_peserta')->label('Kode Peserta'),
            TextColumn::make('keterangan')->label('Keterangan'),
            TextColumn::make('kode_soal')->label('Kode Soal'),
            TextColumn::make('harga')
            ->label('Harga')
            ->getStateUsing(function ($record) {
                return $record->debet > 0
                    ? $record->debet
                    : ($record->kredit > 0 ? $record->kredit : 0);
            })
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
            ->sortable(),
            TextColumn::make('total_saldo')->label('Saldo')->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
