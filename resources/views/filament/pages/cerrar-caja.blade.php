<x-filament-panels::page>

        @if($this->cajaActual)
        <x-filament::section>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #dbeafe; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 500; color: #1e40af; flex-shrink: 0;">
                    {{ strtoupper(substr($this->cajaActual->usuario->name, 0, 2)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 14px; font-weight: 500; margin: 0;">{{ $this->cajaActual->usuario->name }}</p>
                    <p style="font-size: 12px; color: #6b7280; margin: 0;">Responsable del turno</p>
                </div>
                <span style="display: inline-flex; align-items: center; gap: 6px; background: #dcfce7; color: #166534; font-size: 12px; font-weight: 500; padding: 4px 10px; border-radius: 99px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #16a34a; display: inline-block;"></span>
                    Caja abierta
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f3f4f6;">
                <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
                    <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Apertura</p>
                    <p style="font-size: 13px; font-weight: 500; margin: 0;">{{ $this->cajaActual->fecha_apertura->format('d/m/Y H:i') }}</p>
                </div>
                <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
                    <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Monto inicial</p>
                    <p style="font-size: 13px; font-weight: 500; color: #0f766e; margin: 0;">$ {{ number_format($this->cajaActual->monto_inicial, 2, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::section>
        @endif

        {{-- Resumen del día --}}
<x-filament::section>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: #faf5ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-heroicon-o-chart-bar style="width: 16px; height: 16px; color: #7c3aed;" />
        </div>
        <div>
            <h2 style="font-size: 14px; font-weight: 500; margin: 0;">Resumen del turno</h2>
            <p style="font-size: 12px; color: #6b7280; margin: 0;">{{ $this->cantidadVentas }} {{ $this->cantidadVentas === 1 ? 'venta realizada' : 'ventas realizadas' }}</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
        <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
            <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Efectivo</p>
            <p style="font-size: 16px; font-weight: 600; color: #0f766e; margin: 0;">
                ${{ number_format($this->totalEfectivoVentas, 2, ',', '.') }}
            </p>
        </div>
        <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
            <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Tarjeta</p>
            <p style="font-size: 16px; font-weight: 600; color: #1d4ed8; margin: 0;">
                ${{ number_format($this->totalTarjetaVentas, 2, ',', '.') }}
            </p>
        </div>
        <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
            <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Transferencia</p>
            <p style="font-size: 16px; font-weight: 600; color: #92400e; margin: 0;">
                ${{ number_format($this->totalTransferenciaVentas, 2, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Total general --}}
    <div style="margin-top: 12px; padding: 12px 14px; background: #f0fdf4; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; font-weight: 500; color: #374151;">Total recaudado</span>
        <span style="font-size: 20px; font-weight: 700; color: #0f766e;">
            ${{ number_format($this->totalEfectivoVentas + $this->totalTarjetaVentas + $this->totalTransferenciaVentas, 2, ',', '.') }}
        </span>
    </div>
</x-filament::section>
{{-- Diferencia --}}
<x-filament::section>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
        <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <x-heroicon-o-scale style="width: 16px; height: 16px; color: #1d4ed8;" />
        </div>
        <div>
            <h2 style="font-size: 14px; font-weight: 500; margin: 0;">Cuadre de caja</h2>
            <p style="font-size: 12px; color: #6b7280; margin: 0;">Comparación entre lo esperado y lo contado</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
        <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
            <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Monto inicial</p>
            <p style="font-size: 15px; font-weight: 600; color: #374151; margin: 0;">
                ${{ number_format($this->cajaActual->monto_inicial, 2, ',', '.') }}
            </p>
        </div>
        <div style="background: #f9fafb; border-radius: 8px; padding: 12px 14px;">
            <p style="font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; margin: 0 0 4px;">Ventas en efectivo</p>
            <p style="font-size: 15px; font-weight: 600; color: #0f766e; margin: 0;">
                ${{ number_format($this->totalEfectivoVentas, 2, ',', '.') }}
            </p>
        </div>
    </div>

    <div style="padding: 14px 16px; background: #eff6ff; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 13px; font-weight: 500; color: #1e40af;">Efectivo esperado en caja</span>
        <span style="font-size: 20px; font-weight: 700; color: #1d4ed8;">
            ${{ number_format($this->efectivoEsperado, 2, ',', '.') }}
        </span>
    </div>

    <p style="font-size: 12px; color: #9ca3af; margin: 10px 0 0; text-align: center;">
        Ingresá el monto contado en la sección de abajo para ver la diferencia al cerrar.
    </p>
</x-filament::section>

        <x-filament::section>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-lock-closed style="width: 18px; height: 18px; color: #b91c1c;" />
                </div>
                <div>
                    <h2 style="font-size: 15px; font-weight: 500; margin: 0;">Cerrar caja del día</h2>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Contá el efectivo disponible antes de continuar</p>
                </div>
            </div>

            <form wire:submit="cerrarCaja" id="form-cerrar-caja" style="display: flex; flex-direction: column; gap: 16px;">
                {{ $this->form }}

                <div style="display: flex; align-items: flex-start; gap: 10px; background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 12px 14px;">
                    <x-heroicon-o-exclamation-triangle style="width: 18px; height: 18px; color: #b45309; flex-shrink: 0; margin-top: 1px;" />
                    <p style="font-size: 13px; color: #92400e; margin: 0; line-height: 1.5;">
                        Esta acción no se puede deshacer. Asegurate de haber contado el efectivo antes de cerrar.
                    </p>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 16px; border-top: 1px solid #f3f4f6;">
                    <x-filament::actions :actions="$this->getFormActions()" />
                </div>
            </form>
        </x-filament::section>
</x-filament-panels::page>