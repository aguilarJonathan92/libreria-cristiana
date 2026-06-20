<div style="min-height: 100vh; background: #f9fafb; display: flex; align-items: center; justify-content: center; padding: 24px;">
    <div style="width: 100%; max-width: 400px;">

        {{-- Logo / encabezado --}}
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 56px; height: 56px; border-radius: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <x-heroicon-o-book-open style="width: 28px; height: 28px; color: #15803d;" />
            </div>
            <h1 style="font-size: 20px; font-weight: 600; color: #111827; margin: 0 0 4px;">Librería Cristiana POS</h1>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">Ingresá tus credenciales para continuar</p>
        </div>

        {{-- Card del formulario --}}
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">

            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <div style="margin-top: 24px;">
                    <x-filament::button
                        type="submit"
                        wire:loading.attr="disabled"
                        style="width: 100%;"
                        color="success"
                        size="lg"
                    >
                        <span wire:loading.remove>Ingresar</span>
                        <span wire:loading>Verificando...</span>
                    </x-filament::button>
                </div>
            </x-filament-panels::form>

        </div>

        <p style="text-align: center; font-size: 12px; color: #9ca3af; margin-top: 24px;">
            Sistema de gestión interno · Acceso restringido
        </p>

    </div>
</div>