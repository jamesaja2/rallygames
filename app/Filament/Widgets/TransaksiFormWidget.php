<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use App\Models\Peserta;
use App\Models\Soal;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class TransaksiFormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.transaksi-form-widget';

    public ?int $peserta_id = null;
    public ?string $keterangan = null;
    public ?string $kode_soal = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
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
                ->nullable(),
        ])->statePath('');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        Transaksi::create($data);

        Notification::make()
            ->title('Transaksi berhasil disimpan!')
            ->success()
            ->send();

        $this->form->fill();
    }
}
