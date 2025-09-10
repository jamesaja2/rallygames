<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class SettingResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['Super Admin']);
    }

    protected static ?string $model = Setting::class;

    protected static ?string $pluralModelLabel = 'Pengaturan';
    
    protected static ?string $modelLabel = 'Pengaturan';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')
                    ->label('Kunci')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'string' => 'String',
                        'boolean' => 'Boolean',
                        'integer' => 'Integer',
                        'json' => 'JSON'
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\Group::make([
                    TextInput::make('value')
                        ->label('Nilai')
                        ->visible(fn ($get) => in_array($get('type'), ['string', 'integer']))
                        ->required(),

                    Toggle::make('value')
                        ->label('Nilai')
                        ->visible(fn ($get) => $get('type') === 'boolean'),

                    Textarea::make('value')
                        ->label('Nilai JSON')
                        ->visible(fn ($get) => $get('type') === 'json')
                        ->rows(4),
                ]),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'boolean' => 'success',
                        'integer' => 'warning',
                        'json' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('formatted_value')
                    ->label('Nilai')
                    ->getStateUsing(function ($record) {
                        if ($record->type === 'boolean') {
                            return $record->formatted_value ? 'Aktif' : 'Tidak Aktif';
                        }
                        if ($record->type === 'json') {
                            return 'JSON Data';
                        }
                        return $record->formatted_value;
                    })
                    ->badge()
                    ->color(fn ($record): string => match ($record->type) {
                        'boolean' => $record->formatted_value ? 'success' : 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'string' => 'String',
                        'boolean' => 'Boolean',
                        'integer' => 'Integer',
                        'json' => 'JSON'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
