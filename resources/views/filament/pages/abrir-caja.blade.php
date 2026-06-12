<x-filament-panels::page>
    <form wire:submit="abrirCaja" id="form-abrir-caja" class="grid gap-y-6">
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getFormActions()"
        />
    </form>
</x-filament-panels::page>