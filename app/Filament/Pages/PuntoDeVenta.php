<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use App\Enums\MetodoPago;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Producto;
use App\Services\VentaService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PuntoDeVenta extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Punto de Venta';
    protected static UnitEnum|string|null $navigationGroup = 'Caja';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.punto-de-venta';

    // ─── Estado del buscador ─────────────────────────────────────────
    public string $busqueda = '';
    public array $resultados = [];

    // ─── Estado del carrito ──────────────────────────────────────────
    // Estructura: [['producto_id', 'nombre', 'precio_unitario', 'cantidad', 'subtotal'], ...]
    public array $carrito = [];

    // ─── Estado del cobro ────────────────────────────────────────────
    public string $metodoPago = 'efectivo';
    public float $montoRecibido = 0;
    public ?int $clienteId = null;

    // ─── Computed ────────────────────────────────────────────────────
    public function getTotalCarritoProperty(): float
    {
        return collect($this->carrito)->sum('subtotal');
    }

    public function getVueltoProperty(): float
    {
        $vuelto = $this->montoRecibido - $this->totalCarrito;
        return max(0, $vuelto);
    }

    public function getClientesProperty(): array
    {
        return Cliente::orderBy('nombre')
            ->get(['id', 'nombre'])
            ->map(fn($c) => ['value' => $c->id, 'label' => $c->nombre])
            ->toArray();
    }

    // ─── Buscador de productos ───────────────────────────────────────
    public function updatedBusqueda(): void
    {
        if (strlen($this->busqueda) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Producto::where(function ($q) {
                $q->where('nombre', 'like', "%{$this->busqueda}%")
                  ->orWhere('sku', 'like', "%{$this->busqueda}%")
                  ->orWhere('codigo_barras', $this->busqueda);
            })
            ->where('stock', '>', 0)
            ->limit(8)
            ->get(['id', 'nombre', 'sku', 'precio_venta', 'stock'])
            ->toArray();
    }

    // ─── Carrito ─────────────────────────────────────────────────────
    public function agregarAlCarrito(int $productoId): void
    {
        $producto = Producto::find($productoId);

        if (!$producto || $producto->stock <= 0) {
            Notification::make()
                ->title('Sin stock disponible')
                ->warning()
                ->send();
            return;
        }

        // Si ya está en el carrito, incrementar cantidad
        $index = collect($this->carrito)
            ->search(fn($item) => $item['producto_id'] === $productoId);

        if ($index !== false) {
            $cantidadActual = $this->carrito[$index]['cantidad'];

            if ($cantidadActual >= $producto->stock) {
                Notification::make()
                    ->title('Stock máximo alcanzado')
                    ->warning()
                    ->send();
                return;
            }

            $this->carrito[$index]['cantidad']++;
            $this->carrito[$index]['subtotal'] =
                $this->carrito[$index]['cantidad'] * $this->carrito[$index]['precio_unitario'];
        } else {
            $this->carrito[] = [
                'producto_id'     => $producto->id,
                'nombre'          => $producto->nombre,
                'precio_unitario' => (float) $producto->precio_venta,
                'cantidad'        => 1,
                'subtotal'        => (float) $producto->precio_venta,
                'stock_disponible'=> $producto->stock,
            ];
        }

        // Limpiar buscador
        $this->busqueda = '';
        $this->resultados = [];
    }

    public function cambiarCantidad(int $index, int $cantidad): void
    {
        if ($cantidad <= 0) {
            $this->eliminarDelCarrito($index);
            return;
        }

        $stockDisponible = $this->carrito[$index]['stock_disponible'];

        if ($cantidad > $stockDisponible) {
            Notification::make()
                ->title("Stock máximo disponible: {$stockDisponible}")
                ->warning()
                ->send();
            return;
        }

        $this->carrito[$index]['cantidad'] = $cantidad;
        $this->carrito[$index]['subtotal'] =
            $cantidad * $this->carrito[$index]['precio_unitario'];
    }

    public function eliminarDelCarrito(int $index): void
    {
        array_splice($this->carrito, $index, 1);
    }

    public function limpiarCarrito(): void
    {
        $this->carrito = [];
        $this->busqueda = '';
        $this->resultados = [];
        $this->montoRecibido = 0;
        $this->clienteId = null;
        $this->metodoPago = 'efectivo';
    }

    // ─── Confirmar venta ─────────────────────────────────────────────
    public function confirmarVenta(): void
    {
        // Validaciones previas
        if (empty($this->carrito)) {
            Notification::make()
                ->title('El carrito está vacío')
                ->warning()
                ->send();
            return;
        }

        $caja = Caja::abierta()->first();

        if (!$caja) {
            Notification::make()
                ->title('No hay caja abierta')
                ->body('Abrí la caja antes de registrar una venta.')
                ->danger()
                ->send();
            return;
        }

        try {
            $service = new VentaService();

            $items = collect($this->carrito)->map(fn($item) => [
                'producto_id'     => $item['producto_id'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
            ])->toArray();

            $venta = $service->crearVentaContado(
                items: $items,
                metodoPago: MetodoPago::from($this->metodoPago),
                usuario: Auth::user(),
                caja: $caja,
                clienteId: $this->clienteId,
            );

            // Guardar ID para el comprobante antes de limpiar
            $ventaId = $venta->id;

            $this->limpiarCarrito();

            Notification::make()
                ->title('Venta registrada correctamente')
                ->body("Venta #{$ventaId} — Total: $" . number_format($venta->total, 2, ',', '.'))
                ->success()
                ->send();

            // Redirigir al comprobante
            $this->redirect('/admin/comprobante-venta/' . $ventaId);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al registrar la venta')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // Bloquear acceso si no hay caja abierta al montar la página
    public function mount(): void
    {
        if (!Caja::abierta()->exists()) {
            Notification::make()
                ->title('Abrí la caja primero')
                ->warning()
                ->send();

            $this->redirect(route('filament.admin.pages.abrir-caja'));
        }
    }
}