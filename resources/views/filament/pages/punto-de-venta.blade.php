<x-filament-panels::page>
<style>
    .fi-page-content { max-width: 100% !important; }
</style>

<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; align-items: start;">

    {{-- ═══════════════════════════════════════════
         COLUMNA IZQUIERDA — Buscador + Carrito (2/3)
    ════════════════════════════════════════════ --}}
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 16px;">

        {{-- Buscador --}}
        <x-filament::section>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-magnifying-glass style="width: 16px; height: 16px; color: #1d4ed8;" />
                </div>
                <h2 style="font-size: 14px; font-weight: 500; margin: 0;">Buscar producto</h2>
            </div>

            <div style="position: relative;">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="busqueda"
                    placeholder="Nombre, SKU o código de barras..."
                    style="width: 100%; height: 42px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0 14px; font-size: 14px; background: #f9fafb; box-sizing: border-box;"
                />

                @if(count($resultados) > 0)
                <div style="position: absolute; z-index: 50; top: 100%; left: 0; right: 0; margin-top: 4px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
                    @foreach($resultados as $producto)
                    <button
                        wire:click="agregarAlCarrito({{ $producto['id'] }})"
                        style="display: flex; width: 100%; align-items: center; justify-content: space-between; padding: 10px 14px; text-align: left; font-size: 13px; border: none; background: white; cursor: pointer; border-bottom: 1px solid #f3f4f6;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'"
                    >
                        <div>
                            <span style="font-weight: 500; color: #111827;">{{ $producto['nombre'] }}</span>
                            <span style="margin-left: 8px; font-size: 11px; color: #9ca3af;">{{ $producto['sku'] }}</span>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-weight: 600; color: #0f766e;">${{ number_format($producto['precio_venta'], 2, ',', '.') }}</span>
                            <span style="margin-left: 8px; font-size: 11px; color: #9ca3af;">Stock: {{ $producto['stock'] }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </x-filament::section>

        {{-- Carrito --}}
        <x-filament::section>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-shopping-cart style="width: 16px; height: 16px; color: #15803d;" />
                </div>
                <h2 style="font-size: 14px; font-weight: 500; margin: 0;">
                    Carrito
                    @if(count($carrito) > 0)
                        <span style="margin-left: 6px; font-size: 12px; font-weight: 400; color: #9ca3af;">
                            ({{ count($carrito) }} {{ count($carrito) === 1 ? 'ítem' : 'ítems' }})
                        </span>
                    @endif
                </h2>
            </div>

            @if(empty($carrito))
                <div style="padding: 40px 0; text-align: center;">
                    <x-heroicon-o-shopping-bag style="width: 36px; height: 36px; color: #d1d5db; margin: 0 auto 8px;" />
                    <p style="font-size: 13px; color: #9ca3af; margin: 0;">Buscá un producto para agregarlo al carrito.</p>
                </div>
            @else
                <div>
                    @foreach($carrito as $index => $item)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">

                        {{-- Nombre --}}
                        <div style="flex: 1; min-width: 0;">
                            <p style="font-size: 13px; font-weight: 500; color: #111827; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $item['nombre'] }}
                            </p>
                            <p style="font-size: 11px; color: #9ca3af; margin: 2px 0 0;">
                                ${{ number_format($item['precio_unitario'], 2, ',', '.') }} c/u
                            </p>
                        </div>

                        {{-- Cantidad --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button
                                wire:click="cambiarCantidad({{ $index }}, {{ $item['cantidad'] - 1 }})"
                                style="width: 28px; height: 28px; border-radius: 50%; border: 1px solid #e5e7eb; background: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280;"
                            >−</button>
                            <span style="width: 28px; text-align: center; font-size: 14px; font-weight: 600;">{{ $item['cantidad'] }}</span>
                            <button
                                wire:click="cambiarCantidad({{ $index }}, {{ $item['cantidad'] + 1 }})"
                                style="width: 28px; height: 28px; border-radius: 50%; border: 1px solid #e5e7eb; background: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280;"
                            >+</button>
                        </div>

                        {{-- Subtotal --}}
                        <div style="width: 80px; text-align: right;">
                            <span style="font-size: 13px; font-weight: 600; color: #111827;">
                                ${{ number_format($item['subtotal'], 2, ',', '.') }}
                            </span>
                        </div>

                        {{-- Eliminar --}}
                        <button
                            wire:click="eliminarDelCarrito({{ $index }})"
                            style="width: 28px; height: 28px; border-radius: 6px; border: none; background: #fef2f2; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"
                            onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'"
                        >
                            <x-heroicon-o-x-mark style="width: 14px; height: 14px; color: #ef4444;" />
                        </button>
                    </div>
                    @endforeach

                    {{-- Total --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                        <span style="font-size: 14px; font-weight: 500; color: #374151;">Total</span>
                        <span style="font-size: 22px; font-weight: 700; color: #0f766e;">
                            ${{ number_format($this->totalCarrito, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endif
        </x-filament::section>
    </div>

    {{-- ═══════════════════════════════════════════
         COLUMNA DERECHA — Cobro (1/3)
    ════════════════════════════════════════════ --}}
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <x-filament::section>

            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #faf5ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-credit-card style="width: 16px; height: 16px; color: #7c3aed;" />
                </div>
                <h2 style="font-size: 14px; font-weight: 500; margin: 0;">Cobro</h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">

                {{-- Método de pago --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px;">Método de pago</label>
                    <select
                        wire:model.live="metodoPago"
                        style="width: 100%; height: 38px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0 10px; font-size: 13px; background: #f9fafb;"
                    >
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                        <option value="transferencia">🔄 Transferencia</option>
                    </select>
                </div>

                {{-- Monto recibido --}}
                @if($metodoPago === 'efectivo')
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px;">Monto recibido</label>
                    <input
                        type="number"
                        wire:model.live="montoRecibido"
                        min="0"
                        step="0.01"
                        style="width: 100%; height: 38px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0 10px; font-size: 13px; background: #f9fafb; box-sizing: border-box;"
                    />
                    @if($montoRecibido > 0 && $this->vuelto >= 0)
                    <div style="margin-top: 8px; background: #f0fdf4; border-radius: 6px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 12px; color: #15803d;">Vuelto</span>
                        <span style="font-size: 15px; font-weight: 700; color: #15803d;">
                            ${{ number_format($this->vuelto, 2, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Cliente --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 6px;">Cliente <span style="font-weight: 400; color: #9ca3af;">(opcional)</span></label>
                    <select
                        wire:model.live="clienteId"
                        style="width: 100%; height: 38px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0 10px; font-size: 13px; background: #f9fafb;"
                    >
                        <option value="">— Venta anónima —</option>
                        @foreach($this->clientes as $cliente)
                        <option value="{{ $cliente['value'] }}">{{ $cliente['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Resumen --}}
                <div style="background: #f9fafb; border-radius: 10px; padding: 14px; border: 1px solid #f3f4f6;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                        <span>Subtotal</span>
                        <span>${{ number_format($this->totalCarrito, 2, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <span style="font-size: 13px; font-weight: 500;">Total</span>
                        <span style="font-size: 20px; font-weight: 700; color: #0f766e;">
                            ${{ number_format($this->totalCarrito, 2, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Confirmar --}}
                <button
                    wire:click="confirmarVenta"
                    wire:loading.attr="disabled"
                    style="width: 100%; height: 46px; background: #16a34a; border: none; border-radius: 10px; color: white; font-size: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"
                    onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'"
                >
                    <x-heroicon-o-check-circle style="width: 18px; height: 18px;" />
                    <span wire:loading.remove wire:target="confirmarVenta">Confirmar venta</span>
                    <span wire:loading wire:target="confirmarVenta">Procesando...</span>
                </button>

                {{-- Limpiar --}}
                <button
                    wire:click="limpiarCarrito"
                    wire:confirm="¿Limpiar el carrito? Se perderán los ítems cargados."
                    style="width: 100%; height: 36px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; color: #6b7280; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;"
                    onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'"
                >
                    <x-heroicon-o-trash style="width: 14px; height: 14px;" />
                    Limpiar carrito
                </button>

            </div>
        </x-filament::section>
    </div>

</div>
</x-filament-panels::page>