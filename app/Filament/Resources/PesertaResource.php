<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Filament\Resources\PesertaResource\RelationManagers;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Soal;
use Filament\Tables\Columns\TextColumn;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertaImport;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;



class PesertaResource extends Resource
{
            public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Koord Juri']);
    }

    protected static ?string $model = Peserta::class;
    protected static ?string $pluralModelLabel = 'Peserta';
    
    protected static ?string $modelLabel = 'Peserta';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('kode_peserta')
                ->label('Kode Peserta')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('smp_asal')->required(),
            TextInput::make('nama_tim')->required(),
            TextInput::make('saldo')->numeric()->required(),
            TextInput::make('anggota_1')->label('Anggota 1'),
            TextInput::make('anggota_2')->label('Anggota 2'),
            TextInput::make('anggota_3')->label('Anggota 3'),

            Select::make('soal_gratis')
                ->label('Soal Gratis')
                ->options(Soal::all()->pluck('kode_soal', 'kode_soal'))
                ->multiple()
                ->searchable()
                ->preload(),
            ]);
    }





    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('kode_peserta')->label('Kode Peserta')->searchable(),
            TextColumn::make('nama_tim')->label('Nama Tim')->searchable(),
            TextColumn::make('smp_asal')->label('SMP Asal')->searchable(),
            TextColumn::make('anggota_1'),
            TextColumn::make('anggota_2'),
            TextColumn::make('anggota_3'),
            TextColumn::make('saldo')->label('Saldo')->money('IDR'),
            ])
            ->headerActions([
            Action::make('Import Excel')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih file Excel')
                        ->disk('public') // ini penting!
                        ->directory('temp') // opsional, untuk memisahkan
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::import(new PesertaImport, Storage::disk('public')->path($data['file']));
                })
                ->modalHeading('Import Data Peserta')
                ->color('success'),
            Action::make('Unduh Laporan Ringkasan')
                ->url(route('laporan.ringkasan'))
                ->openUrlInNewTab()
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray'),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Export PDF')
    ->url(fn (Peserta $record) => route('peserta.export', $record))
    ->openUrlInNewTab()
    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    BulkAction::make('assign_soal_gratis')
                        ->label('Assign Soal Gratis')
                        ->icon('heroicon-o-gift')
                        ->color('success')
                        ->form([
                            Select::make('soal_gratis')
                                ->label('Pilih Soal Gratis')
                                ->options(Soal::all()->pluck('kode_soal', 'kode_soal'))
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'soal_gratis' => $data['soal_gratis']
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('set_modal')
                        ->label('Set Modal')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('warning')
                        ->form([
                            TextInput::make('modal_amount')
                                ->label('Jumlah Modal')
                                ->numeric()
                                ->default(50000)
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'saldo' => $data['modal_amount']
                                ]);
                                
                                // Buat transaksi modal
                                \App\Models\Transaksi::create([
                                    'peserta_id' => $record->id,
                                    'keterangan' => 'Modal',
                                    'debet' => 0,
                                    'kredit' => $data['modal_amount'],
                                    'total_saldo' => $data['modal_amount'],
                                    'modal_amount' => $data['modal_amount'],
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}




