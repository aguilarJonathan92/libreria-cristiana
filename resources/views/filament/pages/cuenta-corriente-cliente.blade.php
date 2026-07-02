<x-filament-panels::page>
    <div class="space-y-6">

        {{-- ── Resumen del cliente ── --}}
        <x-filament::section>
            <x-slot name="heading">{{ $cliente->nombre }}</x-slot>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <p class="text-xs text-gray-500">Teléfono</p>
                    <p class="font-medium">{{ $cliente->telefono ?? '—' }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <p class="text-xs text-gray-500">Límite de crédito</p>
                    <p class="font-medium">${{ number_format($cliente->limite_credito, 2, ',', '.') }}</p>
                </div>
                <div class="rounded-lg p-4 {{ $cliente->tieneDeuda() ? 'bg-red-50 dark:bg-red-900' : 'bg-green-50 dark:bg-green-900' }}">
                    <p class="text-xs text-gray-500">Saldo deudor</p>
                    <p class="text-lg font-bold {{ $cliente->tieneDeuda() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        ${{ number_format($cliente->saldo_actual, 2, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <p class="text-xs text-gray-500">Crédito disponible</p>
                    <p class="font-medium">${{ number_format($cliente->creditoDisponible(), 2, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::section>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- ── Ventas financiadas ── --}}
            <x-filament::section>
                <x-slot name="heading">Ventas Financiadas</x-slot>

                @forelse($cliente->ventas as $venta)
                <div
                    wire:click="seleccionarVenta({{ $venta->id }})"
                    class="mb-3 cursor-pointer rounded-lg border p-4 transition hover:border-primary-400
                        {{ $ventaSeleccionadaId === $venta->id
                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900'
                            : 'border-gray-200 dark:border-gray-700' }}"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold">Venta #{{ $venta->id }}</p>
                            <p class="text-xs text-gray-400">{{ $venta->fecha_hora->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total: ${{ number_format($venta->total, 2, ',', '.') }}</p>
                            @if($venta->saldo_pendiente > 0)
                            <p class="text-sm font-bold text-red-600">
                                Debe: ${{ number_format($venta->saldo_pendiente, 2, ',', '.') }}
                            </p>
                            @else
                            <p class="text-sm font-bold text-green-600">✓ Saldada</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">Este cliente no tiene ventas financiadas.</p>
                @endforelse
            </x-filament::section>

            {{-- ── Registrar pago ── --}}
            <div class="space-y-4">
                <x-filament::section>
                    <x-slot name="heading">
                        Registrar Pago
                        @if($ventaSeleccionadaId)
                        <span class="ml-2 text-sm font-normal text-primary-600">
                            — Venta #{{ $ventaSeleccionadaId }}
                        </span>
                        @endif
                    </x-slot>

                    @if(!$ventaSeleccionadaId)
                    <p class="text-sm text-gray-400">
                        Seleccioná una venta de la lista para registrar un pago.
                    </p>
                    @else
                    <form wire:submit="registrarPago" class="grid gap-y-4">
                        {{ $this->form }}
                        <x-filament::actions :actions="$this->getFormActions()" />
                    </form>
                    @endif
                </x-filament::section>

                {{-- ── Historial de pagos ── --}}
                <x-filament::section>
                    <x-slot name="heading">Historial de Pagos</x-slot>

                    @forelse($cliente->pagosFinanciacion as $pago)
                    <div class="mb-2 flex items-center justify-between border-b border-gray-100 pb-2 text-sm dark:border-gray-700">
                        <div>
                            <p class="font-medium">${{ number_format($pago->monto_pagado, 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">{{ $pago->fecha_pago->format('d/m/Y H:i') }} — Venta #{{ $pago->venta_id }}</p>
                            @if($pago->notas)
                            <p class="text-xs italic text-gray-400">{{ $pago->notas }}</p>
                            @endif
                        </div>
                        <div class="text-right text-xs text-gray-400">
                            <p>Antes: ${{ number_format($pago->saldo_anterior, 2, ',', '.') }}</p>
                            <p>Después: ${{ number_format($pago->saldo_posterior, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Sin pagos registrados.</p>
                    @endforelse
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>