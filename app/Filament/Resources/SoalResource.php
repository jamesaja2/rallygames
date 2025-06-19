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
                TextInput::make('kode_soal')->required(),
                Select::make('mapel')
                    ->options([
                        'Matematika' => 'Matematika',
                        'Fisika' => 'Fisika',
                        'Biologi' => 'Biologi',
                    ])->required(),
                TextInput::make('harga_beli')->numeric()->required(),
                TextInput::make('harga_benar')->numeric()->required(),
                TextInput::make('harga_salah')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('kode_soal')->label('Kode Soal')->searchable(),
            TextColumn::make('mapel')->label('Mata Pelajaran')->searchable(),
            TextColumn::make('harga_beli')->label('Harga Beli')->money('IDR'),
            TextColumn::make('harga_benar')->label('Harga Jual Benar')->money('IDR'),
            TextColumn::make('harga_salah')->label('Harga Jual Salah')->money('IDR'),
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

