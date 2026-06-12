<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ═══════════════════════════════════════════════
             COLUMNA IZQUIERDA — Buscador y carrito (2/3)
        ════════════════════════════════════════════════ --}}
        <div class="space-y-4 lg:col-span-2">

            {{-- Buscador --}}
            <x-filament::section>
                <x-slot name="heading">Buscar Producto</x-slot>

                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="busqueda"
                        placeholder="Nombre, SKU o código de barras..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    />

                    {{-- Resultados del buscador --}}
                    @if(count($resultados) > 0)
                    <div class="absolute z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                        @foreach($resultados as $producto)
                        <button
                            wire:click="agregarAlCarrito({{ $producto['id'] }})"
                            class="flex w-full items-center justify-between px-4 py-3 text-left text-sm hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $producto['nombre'] }}
                                </span>
                                <span class="ml-2 text-xs text-gray-400">
                                    {{ $producto['sku'] }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-primary-600">
                                    ${{ number_format($producto['precio_venta'], 2, ',', '.') }}
                                </span>
                                <span class="ml-2 text-xs text-gray-400">
                                    Stock: {{ $producto['stock'] }}
                                </span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </x-filament::section>

            {{-- Carrito --}}
            <x-filament::section>
                <x-slot name="heading">
                    Carrito
                    @if(count($carrito) > 0)
                        <span class="ml-2 text-sm font-normal text-gray-400">
                            ({{ count($carrito) }} {{ count($carrito) === 1 ? 'ítem' : 'ítems' }})
                        </span>
                    @endif
                </x-slot>

                @if(empty($carrito))
                    <p class="py-8 text-center text-sm text-gray-400">
                        Buscá un producto para agregarlo al carrito.
                    </p>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($carrito as $index => $item)
                        <div class="flex items-center gap-4 py-3">

                            {{-- Nombre --}}
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item['nombre'] }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    ${{ number_format($item['precio_unitario'], 2, ',', '.') }} c/u
                                </p>
                            </div>

                            {{-- Control de cantidad --}}
                            <div class="flex items-center gap-2">
                                <button
                                    wire:click="cambiarCantidad({{ $index }}, {{ $item['cantidad'] - 1 }})"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700"
                                >−</button>

                                <span class="w-8 text-center text-sm font-semibold">
                                    {{ $item['cantidad'] }}
                                </span>

                                <button
                                    wire:click="cambiarCantidad({{ $index }}, {{ $item['cantidad'] + 1 }})"
                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700"
                                >+</button>
                            </div>

                            {{-- Subtotal --}}
                            <div class="w-24 text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($item['subtotal'], 2, ',', '.') }}
                                </span>
                            </div>

                            {{-- Eliminar --}}
                            <button
                                wire:click="eliminarDelCarrito({{ $index }})"
                                class="text-red-400 hover:text-red-600"
                            >
                                <x-heroicon-o-x-mark class="h-4 w-4" />
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total --}}
                    <div class="mt-4 flex justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                        <span class="text-base font-semibold text-gray-700 dark:text-gray-300">Total</span>
                        <span class="text-xl font-bold text-primary-600">
                            ${{ number_format($this->totalCarrito, 2, ',', '.') }}
                        </span>
                    </div>
                @endif
            </x-filament::section>
        </div>

        {{-- ═══════════════════════════════════════════════
             COLUMNA DERECHA — Cobro (1/3)
        ════════════════════════════════════════════════ --}}
        <div class="space-y-4">

            <x-filament::section>
                <x-slot name="heading">Cobro</x-slot>

                <div class="space-y-4">

                    {{-- Método de pago --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Método de pago
                        </label>
                        <select
                            wire:model.live="metodoPago"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >
                            <option value="efectivo">💵 Efectivo</option>
                            <option value="tarjeta">💳 Tarjeta</option>
                            <option value="transferencia">🔄 Transferencia</option>
                        </select>
                    </div>

                    {{-- Monto recibido (solo efectivo) --}}
                    @if($metodoPago === 'efectivo')
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Monto recibido
                        </label>
                        <input
                            type="number"
                            wire:model.live="montoRecibido"
                            min="0"
                            step="0.01"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        />
                        @if($montoRecibido > 0 && $this->vuelto >= 0)
                        <p class="mt-1 text-sm font-semibold text-green-600">
                            Vuelto: ${{ number_format($this->vuelto, 2, ',', '.') }}
                        </p>
                        @endif
                    </div>
                    @endif

                    {{-- Cliente opcional --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cliente (opcional)
                        </label>
                        <select
                            wire:model.live="clienteId"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >
                            <option value="">— Venta anónima —</option>
                            @foreach($this->clientes as $cliente)
                            <option value="{{ $cliente['value'] }}">{{ $cliente['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Resumen --}}
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span>${{ number_format($this->totalCarrito, 2, ',', '.') }}</span>
                        </div>
                        <div class="mt-2 flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-primary-600">
                                ${{ number_format($this->totalCarrito, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <x-filament::button
                        wire:click="confirmarVenta"
                        wire:loading.attr="disabled"
                        color="success"
                        size="xl"
                        class="w-full"
                        icon="heroicon-o-check-circle"
                    >
                        <span wire:loading.remove wire:target="confirmarVenta">
                            Confirmar Venta
                        </span>
                        <span wire:loading wire:target="confirmarVenta">
                            Procesando...
                        </span>
                    </x-filament::button>

                    <x-filament::button
                        wire:click="limpiarCarrito"
                        wire:confirm="¿Limpiar el carrito? Se perderán los ítems cargados."
                        color="gray"
                        size="sm"
                        class="w-full"
                        icon="heroicon-o-trash"
                    >
                        Limpiar carrito
                    </x-filament::button>

                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
