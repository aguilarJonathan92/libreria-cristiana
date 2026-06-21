<?php

namespace App\Filament\Pages;

use App\Enums\EstadoCaja;
use App\Models\Caja;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Concerns\InteractsWithSchemas;  
use Filament\Schemas\Contracts\HasSchemas;            
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CerrarCaja extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationLabel = 'Caja del Día';
    protected static UnitEnum|string|null $navigationGroup = 'Caja';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.cerrar-caja';

    public ?Caja $cajaActual = null;
    public ?array $data = [];
    public ?float $totalEfectivoVentas = 0;
    public ?float $totalTarjetaVentas = 0;
    public ?float $totalTransferenciaVentas = 0;
    public ?int $cantidadVentas = 0;

    // Solo mostrar en el menú si hay caja abierta
    public static function shouldRegisterNavigation(): bool
    {
        return Caja::abierta()->exists();
    }

    public function mount(): void
    {
        $this->cajaActual = Caja::abierta()->with('usuario')->first();

        if (!$this->cajaActual) {
            redirect()->route('filament.admin.pages.abrir-caja');
            return;
        }

        $ventas = \App\Models\Venta::where('created_at', '>=', $this->cajaActual->fecha_apertura)->get();

        $this->cantidadVentas           = $ventas->count();
        $this->totalEfectivoVentas      = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $this->totalTarjetaVentas       = $ventas->where('metodo_pago', 'tarjeta')->sum('total');
        $this->totalTransferenciaVentas = $ventas->where('metodo_pago', 'transferencia')->sum('total');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('monto_final')
                    ->label('Efectivo en caja al cierre ($)')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->minValue(0),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('cerrar')
                ->label('Cerrar Caja')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('¿Cerrar la caja del día?')
                ->modalDescription('Esta acción no se puede deshacer. Asegurate de haber contado el efectivo.')
                ->action('cerrarCaja'),
        ];
    }

    public function cerrarCaja(): void
    {
        if (!$this->cajaActual) {
            return;
        }

        $datos = $this->form->getState();

        $diferencia = $datos['monto_final'] - $this->efectivoEsperado;

        $this->cajaActual->update([
            'fecha_cierre' => now(),
            'monto_final'  => $datos['monto_final'],
            'diferencia'   => $diferencia,
            'estado'       => EstadoCaja::Cerrada,
        ]);

        $emoji = $diferencia >= 0 ? '✅' : '⚠️';
        $signo = $diferencia >= 0 ? '+' : '';

        Notification::make()
            ->title('Caja cerrada correctamente')
            ->body("{$emoji} Diferencia: {$signo}$" . number_format(abs($diferencia), 2, ',', '.') . ($diferencia < 0 ? ' (faltante)' : ' (sobrante)'))
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.abrir-caja'));
    }

    public function getEfectivoEsperadoProperty(): float
    {
        return $this->cajaActual->monto_inicial + $this->totalEfectivoVentas;
    }
}