<div class="bg-white p-6 rounded-xl shadow">
    {{ $this->form }}

    <div class="mt-6">
        <button
            wire:click="submit"
            class="filament-button bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded-lg w-full"
        >
            Simpan Transaksi
        </button>
    </div>
</div>
