<x-filament-panels::page>
    <div class="mx-auto max-w-lg">
        <x-filament::section>

            {{-- Encabezado --}}
            <div class="mb-6 text-center">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Librería y Regalería Cristiana
                </h2>
                <p class="text-sm text-gray-500">
                    Comprobante de Venta #{{ $venta->id }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $venta->fecha_hora->format('d/m/Y H:i') }}
                </p>
                @if($venta->cliente)
                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Cliente: {{ $venta->cliente->nombre }}
                </p>
                @endif
            </div>

            {{-- Detalle de productos --}}
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-2 text-left font-medium text-gray-500">Producto</th>
                        <th class="pb-2 text-center font-medium text-gray-500">Cant.</th>
                        <th class="pb-2 text-right font-medium text-gray-500">Precio</th>
                        <th class="pb-2 text-right font-medium text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($venta->detalles as $detalle)
                    <tr>
                        <td class="py-2 text-gray-900 dark:text-white">
                            {{ $detalle->producto->nombre }}
                        </td>
                        <td class="py-2 text-center text-gray-600">
                            {{ $detalle->cantidad }}
                        </td>
                        <td class="py-2 text-right text-gray-600">
                            ${{ number_format($detalle->precio_unitario, 2, ',', '.') }}
                        </td>
                        <td class="py-2 text-right font-medium">
                            ${{ number_format($detalle->subtotal(), 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                        <td colspan="3" class="pt-3 text-right font-bold text-gray-900 dark:text-white">
                            TOTAL
                        </td>
                        <td class="pt-3 text-right text-lg font-bold text-primary-600">
                            ${{ number_format($venta->total, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Pie --}}
            <div class="mt-6 border-t border-gray-200 pt-4 text-center dark:border-gray-700">
                <p class="text-xs text-gray-400">
                    Método de pago: {{ $venta->metodo_pago->getLabel() }}
                    · Atendido por: {{ $venta->usuario->name }}
                </p>
                <p class="mt-1 text-xs text-gray-400">¡Gracias por su compra!</p>
            </div>

            {{-- Acciones --}}
            <div class="mt-6 flex gap-3">
                <x-filament::button
                    onclick="window.print()"
                    color="gray"
                    icon="heroicon-o-printer"
                    class="flex-1"
                >
                    Imprimir
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="/admin/punto-de-venta"
                    color="primary"
                    icon="heroicon-o-shopping-cart"
                    class="flex-1"
                >
                    Nueva Venta
                </x-filament::button>
            </div>

        </x-filament::section>
    </div>
</x-filament-panels::page>
