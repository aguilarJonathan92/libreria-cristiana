<x-filament-panels::page>
    <div style="max-width: 42rem; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem;">

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

    </div>
</x-filament-panels::page>