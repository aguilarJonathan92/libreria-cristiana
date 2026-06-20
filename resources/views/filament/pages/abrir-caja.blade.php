<x-filament-panels::page>
    <div style="max-width: 72rem; margin: 0 auto;">

        <x-filament::section>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #dcfce7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-banknotes style="width: 18px; height: 18px; color: #15803d;" />
                </div>
                <div>
                    <h2 style="font-size: 15px; font-weight: 500; margin: 0;">Inicio de Jornada</h2>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Ingresá el monto disponible al inicio del turno para comenzar a registrar operaciones.</p>
                </div>
            </div>

            <form wire:submit="abrirCaja" id="form-abrir-caja" style="display: flex; flex-direction: column; gap: 16px;">
                {{ $this->form }}

                <div style="display: flex; align-items: flex-start; gap: 10px; background: #eff6ff; border: 1px solid #93c5fd; border-radius: 8px; padding: 12px 14px;">
                    <x-heroicon-o-information-circle style="width: 18px; height: 18px; color: #1d4ed8; flex-shrink: 0; margin-top: 1px;" />
                    <p style="font-size: 13px; color: #1e3a8a; margin: 0; line-height: 1.5;">
                        Este monto quedará registrado como el efectivo disponible al inicio del turno.
                    </p>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 16px; border-top: 1px solid #f3f4f6;">
                    <x-filament::actions :actions="$this->getFormActions()" />
                </div>
            </form>
        </x-filament::section>

    </div>
</x-filament-panels::page>