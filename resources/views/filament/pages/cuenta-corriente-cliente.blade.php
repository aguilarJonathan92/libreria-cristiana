<x-filament-panels::page>
<div class="space-y-4">

    {{-- Resumen --}}
    <x-filament::section>
        <x-slot name="heading">{{ $cliente->nombre }}</x-slot>
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">

            <div style="padding:12px; background:#f3f4f6; border-radius:8px;">
                <div style="font-size:11px; color:#6b7280;">TELÉFONO</div>
                <div style="font-size:14px; font-weight:600; margin-top:4px;">{{ $cliente->telefono ?? '—' }}</div>
            </div>

            <div style="padding:12px; background:#f3f4f6; border-radius:8px;">
                <div style="font-size:11px; color:#6b7280;">LÍMITE DE CRÉDITO</div>
                <div style="font-size:14px; font-weight:600; margin-top:4px;">${{ number_format($cliente->limite_credito, 2, ',', '.') }}</div>
            </div>

            <div style="padding:12px; background:{{ $cliente->tieneDeuda() ? '#fef2f2' : '#f0fdf4' }}; border-radius:8px;">
                <div style="font-size:11px; color:{{ $cliente->tieneDeuda() ? '#ef4444' : '#22c55e' }};">SALDO DEUDOR</div>
                <div style="font-size:20px; font-weight:700; margin-top:4px; color:{{ $cliente->tieneDeuda() ? '#dc2626' : '#16a34a' }};">
                    ${{ number_format($cliente->saldo_actual, 2, ',', '.') }}
                </div>
            </div>

            <div style="padding:12px; background:#eff6ff; border-radius:8px;">
                <div style="font-size:11px; color:#3b82f6;">CRÉDITO DISPONIBLE</div>
                <div style="font-size:20px; font-weight:700; margin-top:4px; color:#2563eb;">
                    ${{ number_format($cliente->creditoDisponible(), 2, ',', '.') }}
                </div>
            </div>

        </div>
    </x-filament::section>

    <div style="display:grid; grid-template-columns: 2fr 3fr; gap:16px;">

        {{-- Ventas --}}
        <x-filament::section>
            <x-slot name="heading">Ventas Financiadas</x-slot>

            @forelse($cliente->ventas as $venta)
            <button
                wire:click="seleccionarVenta({{ $venta->id }})"
                style="width:100%; text-align:left; padding:12px; border-radius:8px; margin-bottom:8px; border: 2px solid {{ $ventaSeleccionadaId === $venta->id ? '#6366f1' : '#e5e7eb' }}; background:{{ $ventaSeleccionadaId === $venta->id ? '#eef2ff' : '#ffffff' }}; cursor:pointer;"
            >
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="font-weight:700; font-size:14px;">Venta #{{ $venta->id }}</div>
                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ $venta->fecha_hora->format('d/m/Y H:i') }}</div>
                        <div style="font-size:12px; color:#6b7280; margin-top:4px;">Total: ${{ number_format($venta->total, 2, ',', '.') }}</div>
                    </div>
                    <div>
                        @if($venta->saldo_pendiente > 0)
                        <span style="font-size:11px; font-weight:700; color:#dc2626; background:#fef2f2; padding:3px 8px; border-radius:999px;">
                            Debe ${{ number_format($venta->saldo_pendiente, 2, ',', '.') }}
                        </span>
                        @else
                        <span style="font-size:11px; font-weight:700; color:#16a34a; background:#f0fdf4; padding:3px 8px; border-radius:999px;">
                            ✓ Saldada
                        </span>
                        @endif
                    </div>
                </div>
                @if($ventaSeleccionadaId === $venta->id)
                <div style="font-size:11px; color:#6366f1; margin-top:6px;">✓ Seleccionada</div>
                @endif
            </button>
            @empty
            <p style="font-size:13px; color:#9ca3af; text-align:center; padding:16px 0;">Sin ventas financiadas.</p>
            @endforelse
        </x-filament::section>

        {{-- Panel derecho --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Registrar pago --}}
            <x-filament::section>
                <x-slot name="heading">
                    Registrar Pago
                    @if($ventaSeleccionadaId)
                    <span style="font-size:13px; font-weight:400; color:#6366f1; margin-left:8px;">— Venta #{{ $ventaSeleccionadaId }}</span>
                    @endif
                </x-slot>

                @if(!$ventaSeleccionadaId)
                <p style="font-size:13px; color:#9ca3af; text-align:center; padding:16px 0;">
                    Seleccioná una venta de la lista para registrar un pago.
                </p>
                @else
                    @php $ventaActual = $cliente->ventas->firstWhere('id', $ventaSeleccionadaId); @endphp
                    @if($ventaActual)
                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:16px;">
                        <div style="padding:10px; background:#f3f4f6; border-radius:8px; text-align:center;">
                            <div style="font-size:10px; color:#6b7280;">TOTAL</div>
                            <div style="font-size:13px; font-weight:600; margin-top:2px;">${{ number_format($ventaActual->total, 2, ',', '.') }}</div>
                        </div>
                        <div style="padding:10px; background:#f0fdf4; border-radius:8px; text-align:center;">
                            <div style="font-size:10px; color:#16a34a;">PAGADO</div>
                            <div style="font-size:13px; font-weight:600; margin-top:2px; color:#16a34a;">${{ number_format($ventaActual->total - $ventaActual->saldo_pendiente, 2, ',', '.') }}</div>
                        </div>
                        <div style="padding:10px; background:#fef2f2; border-radius:8px; text-align:center;">
                            <div style="font-size:10px; color:#dc2626;">PENDIENTE</div>
                            <div style="font-size:14px; font-weight:700; margin-top:2px; color:#dc2626;">${{ number_format($ventaActual->saldo_pendiente, 2, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif

                    <form wire:submit="registrarPago" class="grid gap-y-4">
                        {{ $this->form }}
                        <x-filament::actions :actions="$this->getFormActions()" />
                    </form>
                @endif
            </x-filament::section>

            {{-- Historial --}}
            <x-filament::section>
                <x-slot name="heading">Historial de Pagos</x-slot>

                @forelse($cliente->pagosFinanciacion as $pago)
                <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:10px 0; border-bottom:1px solid #f3f4f6;">
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#16a34a;">
                            + ${{ number_format($pago->monto_pagado, 2, ',', '.') }}
                        </div>
                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                            {{ $pago->fecha_pago->format('d/m/Y H:i') }} · Venta #{{ $pago->venta_id }}
                            · <span style="font-weight:600;">{{ $pago->metodo_pago->getLabel() }}</span>
                        </div>
                        @if($pago->notas)
                        <div style="font-size:11px; color:#9ca3af; font-style:italic; margin-top:2px;">"{{ $pago->notas }}"</div>
                        @endif
                    </div>
                    <div style="text-align:right; font-size:11px; color:#9ca3af;">
                        <div>Antes: <span style="color:#dc2626; font-weight:500;">${{ number_format($pago->saldo_anterior, 2, ',', '.') }}</span></div>
                        <div>Después: <span style="color:#16a34a; font-weight:500;">${{ number_format($pago->saldo_posterior, 2, ',', '.') }}</span></div>
                    </div>
                </div>
                @empty
                <p style="font-size:13px; color:#9ca3af; text-align:center; padding:16px 0;">Sin pagos registrados.</p>
                @endforelse
            </x-filament::section>

        </div>
    </div>
</div>
</x-filament-panels::page>