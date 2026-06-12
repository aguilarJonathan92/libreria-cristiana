<?php

namespace App\Filament\Pages;

use App\Enums\EstadoCaja;
use App\Models\Caja;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AbrirCaja extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Abrir Caja';
    protected static UnitEnum|string|null $navigationGroup = 'Caja';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.abrir-caja';

    public ?array $data = [];

    // Ocultar del menú si ya hay caja abierta
    public static function shouldRegisterNavigation(): bool
    {
        return !Caja::abierta()->exists();
    }

    public function mount(): void
    {
        // Si ya hay caja abierta, redirigir
        if (Caja::abierta()->exists()) {
            redirect()->route('filament.admin.pages.cerrar-caja');
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('monto_inicial')
                    ->label('Monto inicial en caja ($)')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->minValue(0)
                    ->helperText('Ingresá el efectivo disponible al inicio del turno.'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('abrir')
                ->label('Abrir Caja')
                ->icon('heroicon-o-lock-open')
                ->action('abrirCaja'),
        ];
    }

    public function abrirCaja(): void
    {
        // Validar que no haya otra caja abierta (doble check a nivel de negocio)
        if (Caja::abierta()->exists()) {
            Notification::make()
                ->title('Ya existe una caja abierta')
                ->warning()
                ->send();
            return;
        }

        $datos = $this->form->getState();

        Caja::create([
            'fecha_apertura' => now(),
            'monto_inicial'  => $datos['monto_inicial'],
            'estado'         => EstadoCaja::Abierta,
            'usuario_id'     => Auth::id(),
        ]);

        Notification::make()
            ->title('Caja abierta correctamente')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.cerrar-caja'));
    }
}