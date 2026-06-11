<?php

namespace App\Filament\Resources\Productos;

use App\Filament\Resources\Productos\Pages\ListProductos;
use App\Filament\Resources\Productos\Pages\EditProducto;
use App\Filament\Resources\Productos\Pages\CreateProducto;
use App\Filament\Resources\Productos\Pages;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';
    protected static string|UnitEnum|null $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('Identificación')
                ->columns(2)
                ->schema([
                    TextInput::make('sku')
                        ->label('SKU')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->placeholder('BIB-RV-TDURA-NEG-MED'),

                    TextInput::make('codigo_barras')
                        ->label('Código de Barras')
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),

                    TextInput::make('nombre')
                        ->label('Nombre del Producto')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('nombre_familia')
                        ->label('Familia / Grupo')
                        ->maxLength(255)
                        ->placeholder('Ej: Biblia Reina Valera 1960')
                        ->helperText('Agrupa variantes del mismo producto en reportes.')
                        ->columnSpanFull(),
                ]),

            Section::make('Detalles Editoriales')
                ->columns(2)
                ->collapsed()
                ->schema([
                    TextInput::make('autor')
                        ->label('Autor')
                        ->maxLength(255),

                    TextInput::make('editorial')
                        ->label('Editorial')
                        ->maxLength(255),
                ]),

            Section::make('Variantes y Atributos')
                ->collapsed()
                ->schema([
                    KeyValue::make('atributos')
                        ->label('Atributos')
                        ->keyLabel('Característica')
                        ->valueLabel('Valor')
                        ->addButtonLabel('Agregar atributo')
                        ->helperText('Ej: tapa → dura, color → negro, tamaño → mediano'),
                ]),

            Section::make('Precios e Inventario')
                ->columns(2)
                ->schema([
                    TextInput::make('precio_costo')
                        ->label('Precio de Costo')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->minValue(0),

                    TextInput::make('precio_venta')
                        ->label('Precio de Venta')
                        ->numeric()
                        ->prefix('$')
                        ->required()
                        ->minValue(0),

                    TextInput::make('stock')
                        ->label('Stock Actual')
                        ->numeric()
                        ->required()
                        ->minValue(0),

                    TextInput::make('stock_minimo')
                        ->label('Stock Mínimo (alerta)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->helperText('Se mostrará alerta cuando el stock llegue a este valor.'),
                ]),

            Section::make('Clasificación')
                ->columns(2)
                ->schema([
                    Select::make('categoria_id')
                        ->label('Categoría')
                        ->relationship('categoria', 'nombre')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('nombre')
                                ->label('Nombre')
                                ->required(),
                        ]),

                    Select::make('proveedor_id')
                        ->label('Proveedor')
                        ->relationship('proveedor', 'nombre')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable()
                    ->badge(),

                TextColumn::make('precio_venta')
                    ->label('Precio Venta')
                    ->money('ARS')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->alignCenter()
                    ->color(
                        fn(Producto $record): string =>
                        $record->tieneStockBajo() ? 'danger' : 'success'
                    )
                    ->weight(
                        fn(Producto $record): string =>
                        $record->tieneStockBajo() ? 'bold' : 'normal'
                    ),

                TextColumn::make('stock_minimo')
                    ->label('Mín.')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre'),

                SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->relationship('proveedor', 'nombre'),

                Filter::make('stock_bajo')
                    ->label('Stock bajo mínimo')
                    ->query(
                        fn(Builder $query) =>
                        $query->whereColumn('stock', '<=', 'stock_minimo')
                    )
                    ->toggle(),
            ])
            ->defaultSort('nombre')
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit'   => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
