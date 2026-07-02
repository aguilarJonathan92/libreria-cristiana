<x-filament-panels::page>
<div class="space-y-4">

    {{-- Responsable del turno --}}
    @if($this->cajaActual)
    <x-filament::section>
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:600; color:#1e40af; flex-shrink:0;">
                {{ strtoupper(substr($this->cajaActual->usuario->name, 0, 2)) }}
            </div>
            <div style="flex:1;">
                <p style="font-size:14px; font-weight:600; margin:0;">{{ $this->cajaActual->usuario->name }}</p>
                <p style="font-size:12px; color:#6b7280; margin:0;">Responsable del turno · Apertura {{ $this->cajaActual->fecha_apertura->format('d/m/Y H:i') }}</p>
            </div>
            <span style="background:#dcfce7; color:#166534; font-size:12px; font-weight:600; padding:4px 12px; border-radius:99px; display:inline-flex; align-items:center; gap:6px;">
                <span style="width:6px; height:6px; border-radius:50%; background:#16a34a; display:inline-block;"></span>
                Caja abierta
            </span>
        </div>
    </x-filament::section>
    @endif

    {{-- Resumen del día --}}
    @if($this->cajaActual)
    <x-filament::section>
        <x-slot name="heading">Resumen del día — {{ $this->cajaActual->fecha_apertura->format('d/m/Y') }}</x-slot>

        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:16px;">

            <div style="padding:14px; background:#f9fafb; border-radius:8px;">
                <p style="font-size:11px; font-weight:600; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Monto inicial</p>
                <p style="font-size:15px; font-weight:600; margin:0;">${{ number_format($this->cajaActual->monto_inicial, 2, ',', '.') }}</p>
            </div>

            <div style="padding:14px; background:#f9fafb; border-radius:8px;">
                <p style="font-size:11px; font-weight:600; text-transform:uppercase; color:#9ca3af; margin:0 0 4px;">Ventas en efectivo</p>
                <p style="font-size:15px; font-weight:600; color:#0f766e; margin:0;">${{ number_format($this->totalEfectivoVentas, 2, ',', '.') }}</p>
            </div>

            <div style="padding:14px; background:#f0fdf4; border-radius:8px;">
                <p style="font-size:11px; font-weight:600; text-transform:uppercase; color:#16a34a; margin:0 0 4px;">Total ventas del día</p>
                <p style="font-size:18px; font-weight:700; color:#16a34a; margin:0;">${{ number_format($this->cajaActual->total_ventas, 2, ',', '.') }}</p>
            </div>

            <div style="padding:14px; background:#eff6ff; border-radius:8px;">
                <p style="font-size:11px; font-weight:600; text-transform:uppercase; color:#3b82f6; margin:0 0 4px;">Cobros financiación</p>
                <p style="font-size:18px; font-weight:700; color:#2563eb; margin:0;">${{ number_format($this->cajaActual->total_cobros_financiacion, 2, ',', '.') }}</p>
            </div>

        </div>

        {{-- Total esperado --}}
        <div style="padding:16px; background:#1e1b4b; border-radius:10px; display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <div>
                <p style="font-size:11px; color:#a5b4fc; font-weight:600; margin:0 0 2px;">TOTAL ESPERADO EN CAJA</p>
                <p style="font-size:11px; color:#6366f1; margin:0;">Inicial + todas las ventas + cobros</p>
            </div>
            <p style="font-size:24px; font-weight:700; color:#ffffff; margin:0;">
                ${{ number_format($this->cajaActual->totalDelDia(), 2, ',', '.') }}
            </p>
        </div>

        {{-- Efectivo esperado --}}
        <div style="padding:14px; background:#eff6ff; border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="font-size:11px; color:#1e40af; font-weight:600; margin:0 0 2px;">EFECTIVO ESPERADO EN CAJA</p>
                <p style="font-size:11px; color:#3b82f6; margin:0;">Inicial + ventas efectivo + cobros financiación</p>
            </div>
            <p style="font-size:20px; font-weight:700; color:#1d4ed8; margin:0;">
                ${{ number_format($this->efectivoEsperado, 2, ',', '.') }}
            </p>
        </div>

    </x-filament::section>
    @endif

    {{-- Cerrar caja --}}
    <x-filament::section>
        <x-slot name="heading">Cerrar caja del día</x-slot>

        <form wire:submit="cerrarCaja" class="grid gap-y-4">
            {{ $this->form }}

            <div style="padding:12px 14px; background:#fffbeb; border:1px solid #fcd34d; border-radius:8px;">
                <p style="font-size:13px; color:#92400e; margin:0;">
                    ⚠️ Esta acción no se puede deshacer. Asegurate de haber contado el efectivo antes de cerrar.
                </p>
            </div>

            <div style="display:flex; justify-content:flex-end; padding-top:12px; border-top:1px solid #f3f4f6;">
                <x-filament::actions :actions="$this->getFormActions()" />
            </div>
        </form>
    </x-filament::section>

</div>
</x-filament-panels::page>