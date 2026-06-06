<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'YouxTopUp';
    public static function form(Form $form): Form { return $form->schema([Forms\Components\Select::make('game_id')->relationship('game','name')->required()->searchable(),
Forms\Components\TextInput::make('name')->required(),
Forms\Components\TextInput::make('price')->numeric()->required()->prefix('Rp'),
Forms\Components\TextInput::make('points')->numeric()->default(0),
Forms\Components\Toggle::make('is_active')->default(true),
Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
Forms\Components\Textarea::make('description')->columnSpanFull(),]); }
    public static function table(Table $table): Table { return $table->columns([Tables\Columns\TextColumn::make('game.name')->sortable()->searchable(),
Tables\Columns\TextColumn::make('name')->searchable(),
Tables\Columns\TextColumn::make('price')->money('IDR'),
Tables\Columns\IconColumn::make('is_active')->boolean(),])->filters([])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]); }
    public static function getPages(): array { return ['index'=>Pages\ListProducts::route('/'), 'create'=>Pages\CreateProduct::route('/create'), 'edit'=>Pages\EditProduct::route('/{record}/edit')]; }
}
