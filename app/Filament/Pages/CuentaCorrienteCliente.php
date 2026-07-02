<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Venta;
use App\Services\PagoService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

class CuentaCorrienteCliente extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Cuenta Corriente';
    protected static UnitEnum|string|null $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.cuenta-corriente-cliente';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'cuenta-corriente/{cliente}';

    public ?Cliente $cliente = null;
    public ?array $data = [];

    // Venta seleccionada para aplicar el pago
    public ?int $ventaSeleccionadaId = null;

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente->load([
            'ventas' => fn($q) => $q->where('tipo_venta', 'financiada')
                                    ->with('detalles')
                                    ->orderByDesc('fecha_hora'),
            'pagosFinanciacion' => fn($q) => $q->orderByDesc('fecha_pago'),
        ]);

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('monto')
                    ->label('Monto del pago ($)')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->minValue(0.01),

                Textarea::make('notas')
                    ->label('Notas (opcional)')
                    ->rows(2),
            ])
            ->statePath('data');
    }

    public function seleccionarVenta(int $ventaId): void
    {
        $this->ventaSeleccionadaId = $ventaId;
        $this->form->fill();
    }

    public function registrarPago(): void
    {
        if (!$this->ventaSeleccionadaId) {
            Notification::make()
                ->title('Seleccioná una venta primero')
                ->warning()
                ->send();
            return;
        }

        $caja = Caja::abierta()->first();

        if (!$caja) {
            Notification::make()
                ->title('No hay caja abierta')
                ->danger()
                ->send();
            return;
        }

        $datos = $this->form->getState();
        $venta = Venta::find($this->ventaSeleccionadaId);

        if (!$venta || $venta->cliente_id !== $this->cliente->id) {
            Notification::make()->title('Venta inválida')->danger()->send();
            return;
        }

        try {
            $service = new PagoService();
            $service->registrarPago(
                venta: $venta,
                monto: $datos['monto'],
                caja: $caja,
                notas: $datos['notas'] ?? null,
            );

            // Recargar cliente con datos actualizados
            $this->cliente->refresh();
            $this->cliente->load([
                'ventas' => fn($q) => $q->where('tipo_venta', 'financiada')
                                        ->with('detalles')
                                        ->orderByDesc('fecha_hora'),
                'pagosFinanciacion' => fn($q) => $q->orderByDesc('fecha_pago'),
            ]);

            $this->ventaSeleccionadaId = null;
            $this->form->fill();

            Notification::make()
                ->title('Pago registrado correctamente')
                ->body("Nuevo saldo: $" . number_format($this->cliente->saldo_actual, 2, ',', '.'))
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al registrar el pago')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('registrarPago')
                ->label('Confirmar Pago')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action('registrarPago'),
        ];
    }
}