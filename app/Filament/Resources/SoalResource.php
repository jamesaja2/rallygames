<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoalResource\Pages;
use App\Filament\Resources\SoalResource\RelationManagers;
use App\Models\Soal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SoalImport;
use Illuminate\Support\Facades\Storage;


class SoalResource extends Resource
{
        public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Koord Juri']);
    }

    protected static ?string $model = Soal::class;
    
    protected static ?string $pluralModelLabel = 'Soal';
    
    protected static ?string $modelLabel = 'Soal';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_soal')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Kode Soal'),
                    
                Select::make('mapel')
                    ->options([
                        'Matematika' => 'Matematika',
                        'Fisika' => 'Fisika',
                        'Biologi' => 'Biologi',
                        'Kimia' => 'Kimia',
                        'IPS' => 'IPS',
                        'Bahasa Indonesia' => 'Bahasa Indonesia',
                        'Bahasa Inggris' => 'Bahasa Inggris',
                    ])
                    ->required()
                    ->label('Mata Pelajaran'),
                    
                Select::make('tipe_soal')
                    ->options([
                        'Pilihan Ganda' => 'Pilihan Ganda',
                        'Essai' => 'Essai',
                        'Pilihan Ganda Kompleks' => 'Pilihan Ganda Kompleks',
                    ])
                    ->required()
                    ->live()
                    ->label('Tipe Soal'),
                    
                TextInput::make('harga_beli')
                    ->numeric()
                    ->required()
                    ->label('Harga Beli'),
                    
                TextInput::make('harga_benar')
                    ->numeric()
                    ->required()
                    ->label('Harga Jual (Benar)'),
                    
                TextInput::make('harga_salah')
                    ->numeric()
                    ->required()
                    ->label('Harga Jual (Salah)'),

                // Kunci Jawaban - Dynamic based on tipe_soal
                Select::make('kunci_jawaban')
                    ->label('Kunci Jawaban')
                    ->options([
                        'A' => 'A',
                        'B' => 'B', 
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'E',
                    ])
                    ->required()
                    ->visible(fn (Get $get): bool => $get('tipe_soal') === 'Pilihan Ganda'),

                CheckboxList::make('kunci_jawaban')
                    ->label('Kunci Jawaban (Pilih yang benar)')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C', 
                        'D' => 'D',
                        'E' => 'E',
                    ])
                    ->required()
                    ->visible(fn (Get $get): bool => $get('tipe_soal') === 'Pilihan Ganda Kompleks')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            return json_decode($state, true) ?? [];
                        }
                        return $state ?? [];
                    })
                    ->dehydrateStateUsing(function ($state) {
                        return is_array($state) ? json_encode($state) : $state;
                    }),

                Textarea::make('kunci_jawaban')
                    ->label('Kunci Jawaban')
                    ->required()
                    ->rows(3)
                    ->visible(fn (Get $get): bool => $get('tipe_soal') === 'Essai')
                    ->helperText('Tulis jawaban yang benar untuk soal essai ini'),

                // Pilihan Jawaban untuk Pilihan Ganda dan Pilihan Ganda Kompleks
                Repeater::make('pilihan_jawaban')
                    ->label('Pilihan Jawaban')
                    ->schema([
                        Select::make('huruf')
                            ->label('Huruf')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'C' => 'C',
                                'D' => 'D',
                                'E' => 'E',
                            ])
                            ->required(),
                        Textarea::make('teks')
                            ->label('Teks Pilihan')
                            ->required()
                            ->rows(2),
                    ])
                    ->visible(fn (Get $get): bool => in_array($get('tipe_soal'), ['Pilihan Ganda', 'Pilihan Ganda Kompleks']))
                    ->defaultItems(5)
                    ->minItems(2)
                    ->maxItems(5)
                    ->itemLabel(fn (array $state): ?string => $state['huruf'] ?? null)
                    ->reorderable(false)
                    ->collapsible()
                    ->helperText('Masukkan pilihan jawaban A, B, C, D, E beserta teksnya'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('kode_soal')->label('Kode Soal')->searchable()->sortable(),
            TextColumn::make('mapel')->label('Mata Pelajaran')->searchable()->sortable(),
            TextColumn::make('tipe_soal')->label('Tipe Soal')->searchable()->sortable()
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Pilihan Ganda' => 'success',
                    'Essai' => 'warning', 
                    'Pilihan Ganda Kompleks' => 'info',
                    default => 'gray',
                }),
            TextColumn::make('harga_beli')->label('Harga Beli')->money('IDR')->sortable(),
            TextColumn::make('harga_benar')->label('Harga Jual Benar')->money('IDR')->sortable(),
            TextColumn::make('harga_salah')->label('Harga Jual Salah')->money('IDR')->sortable(),
            TextColumn::make('kunci_jawaban')->label('Kunci Jawaban')
                ->formatStateUsing(function ($state, $record) {
                    if ($record->tipe_soal === 'Pilihan Ganda Kompleks') {
                        $decoded = json_decode($state, true);
                        return is_array($decoded) ? implode(', ', $decoded) : $state;
                    }
                    return $state;
                })
                ->limit(50),
            ])
           ->headerActions([
                Action::make('Import Excel')
                    ->form([
                        FileUpload::make('file')
                            ->label('Pilih file Excel')
                            ->disk('public') // simpan ke storage/app/public
                            ->directory('temp') // opsional
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        Excel::import(new SoalImport, Storage::disk('public')->path($data['file']));
                    })
                    ->modalHeading('Import Data Soal')
                    ->color('success'),
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
            'index' => Pages\ListSoals::route('/'),
            'create' => Pages\CreateSoal::route('/create'),
            'edit' => Pages\EditSoal::route('/{record}/edit'),
        ];
    }
}

