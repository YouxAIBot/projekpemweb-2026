<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'YouxTopUp';
    public static function form(Form $form): Form { return $form->schema([Forms\Components\TextInput::make('invoice_number')->disabled(),
Forms\Components\TextInput::make('order_id')->disabled(),
Forms\Components\Select::make('game_id')->relationship('game','name')->required()->searchable(),
Forms\Components\Select::make('product_id')->relationship('product','name')->required()->searchable(),
Forms\Components\Select::make('payment_method_id')->relationship('paymentMethod','name')->required()->searchable(),
Forms\Components\Select::make('topup_type')->options(['uid'=>'Via UID','login'=>'Via Login'])->required(),
Forms\Components\Select::make('status')->options(['unpaid'=>'Belum Bayar','processing'=>'Proses','success'=>'Sukses'])->required(),
Forms\Components\TextInput::make('customer_name'),
Forms\Components\TextInput::make('whatsapp')->required(),
Forms\Components\TextInput::make('user_identifier'),
Forms\Components\TextInput::make('server'),
Forms\Components\TextInput::make('subtotal')->numeric()->prefix('Rp'),
Forms\Components\TextInput::make('fee')->numeric()->prefix('Rp'),
Forms\Components\TextInput::make('discount')->numeric()->prefix('Rp'),
Forms\Components\TextInput::make('total')->numeric()->prefix('Rp'),
Forms\Components\Textarea::make('notes')->columnSpanFull(),]); }
    public static function table(Table $table): Table { return $table->columns([Tables\Columns\TextColumn::make('invoice_number')->searchable(),
Tables\Columns\TextColumn::make('game.name'),
Tables\Columns\TextColumn::make('product.name'),
Tables\Columns\TextColumn::make('total')->money('IDR'),
Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) { 'success' => 'success', 'processing'=>'info', default=>'warning' }),
Tables\Columns\TextColumn::make('created_at')->dateTime(),])->filters([])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]); }
    public static function getPages(): array { return ['index'=>Pages\ListOrders::route('/'), 'create'=>Pages\CreateOrder::route('/create'), 'edit'=>Pages\EditOrder::route('/{record}/edit')]; }
}
