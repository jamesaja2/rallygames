<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Resources\Pages\Page;

class ListPesertas extends ListRecords
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableActions(): array
    {
        return [
            Action::make('Export PDF')
                ->label('Export PDF')
                ->url(fn ($record) => route('peserta.export', $record))
                ->openUrlInNewTab()
                ->icon('heroicon-o-arrow-down-tray'),
        ];
    }
}