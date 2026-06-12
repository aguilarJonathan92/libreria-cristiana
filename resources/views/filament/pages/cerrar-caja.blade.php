<x-filament-panels::page>

    @if($this->cajaActual)
    <x-filament::section>
        <x-slot name="heading">Caja abierta por {{ $this->cajaActual->usuario->name }}</x-slot>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-500">Apertura:</span>
                <span class="ml-2">{{ $this->cajaActual->fecha_apertura->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-500">Monto inicial:</span>
                <span class="ml-2">$ {{ number_format($this->cajaActual->monto_inicial, 2, ',', '.') }}</span>
            </div>
        </div>
    </x-filament::section>
    @endif

    <form wire:submit="cerrarCaja" id="form-cerrar-caja" class="grid gap-y-6">
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getFormActions()"
        />
    </form>

</x-filament-panels::page>