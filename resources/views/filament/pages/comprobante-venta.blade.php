<x-filament-panels::page>
<style>
    .fi-page-content { max-width: 100% !important; }
</style>

<div style="max-width: 640px; margin: 0 auto;">
    <x-filament::section>

        {{-- Encabezado --}}
        <div style="text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f3f4f6; margin-bottom: 20px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                <x-heroicon-o-receipt-percent style="width: 24px; height: 24px; color: #15803d;" />
            </div>
            <h2 style="font-size: 17px; font-weight: 600; color: #111827; margin: 0 0 4px;">
                Librería y Regalería Cristiana
            </h2>
            <p style="font-size: 13px; color: #6b7280; margin: 0 0 2px;">
                Comprobante de Venta #{{ $venta->id }}
            </p>
            <p style="font-size: 13px; color: #9ca3af; margin: 0;">
                {{ $venta->fecha_hora->format('d/m/Y H:i') }}
            </p>
            @if($venta->cliente)
            <div style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; background: #eff6ff; border-radius: 99px; padding: 4px 12px;">
                <x-heroicon-o-user style="width: 13px; height: 13px; color: #1d4ed8;" />
                <span style="font-size: 12px; font-weight: 500; color: #1e40af;">{{ $venta->cliente->nombre }}</span>
            </div>
            @endif
        </div>

        {{-- Tabla de productos --}}
        <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 0 0 10px; text-align: left; font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Producto</th>
                    <th style="padding: 0 0 10px; text-align: center; font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Cant.</th>
                    <th style="padding: 0 0 10px; text-align: right; font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Precio</th>
                    <th style="padding: 0 0 10px; text-align: right; font-size: 11px; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr style="border-bottom: 1px solid #f9fafb;">
                    <td style="padding: 10px 0; color: #111827; font-weight: 500;">
                        {{ $detalle->producto->nombre }}
                    </td>
                    <td style="padding: 10px 0; text-align: center; color: #6b7280;">
                        {{ $detalle->cantidad }}
                    </td>
                    <td style="padding: 10px 0; text-align: right; color: #6b7280;">
                        ${{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                    </td>
                    <td style="padding: 10px 0; text-align: right; font-weight: 600; color: #111827;">
                        ${{ number_format($detalle->subtotal(), 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="padding-top: 16px; text-align: right; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                        Total
                    </td>
                    <td style="padding-top: 16px; text-align: right; font-size: 22px; font-weight: 700; color: #0f766e;">
                        ${{ number_format($venta->total, 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Pie --}}
        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-size: 12px; color: #9ca3af; margin: 0 0 2px;">
                    Método de pago: <span style="color: #6b7280; font-weight: 500;">{{ $venta->metodo_pago->getLabel() }}</span>
                </p>
                <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                    Atendido por: <span style="color: #6b7280; font-weight: 500;">{{ $venta->usuario->name }}</span>
                </p>
            </div>
            <p style="font-size: 13px; color: #9ca3af; margin: 0; font-style: italic;">¡Gracias por su compra!</p>
        </div>

        {{-- Acciones --}}
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">

            <button
                onclick="window.print()"
                style="height: 40px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-weight: 500; color: #374151; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;"
                onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'"
            >
                <x-heroicon-o-printer style="width: 16px; height: 16px; color: #6b7280;" />
                Imprimir
            </button>

            
            <a href="/admin/punto-de-venta"
                style="height: 40px; background: #16a34a; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px; text-decoration: none;"
                onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'"
            >
                <x-heroicon-o-shopping-cart style="width: 16px; height: 16px;" />
                Nueva venta
            </a>

        </div>

    </x-filament::section>
</div>
</x-filament-panels::page>