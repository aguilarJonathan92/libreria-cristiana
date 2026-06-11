<?php

namespace App\Filament\Pages;

use App\Enums\EstadoCaja;
use App\Models\Caja;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CerrarCaja extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationLabel = 'Caja del Día';
    protected static UnitEnum|string|null $navigationGroup = 'Caja';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.cerrar-caja';

    public ?Caja $cajaActual = null;
    public ?array $data = [];

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

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

        $this->cajaActual->update([
            'fecha_cierre' => now(),
            'monto_final'  => $datos['monto_final'],
            'estado'       => EstadoCaja::Cerrada,
        ]);

        Notification::make()
            ->title('Caja cerrada correctamente')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.abrir-caja'));
    }
}