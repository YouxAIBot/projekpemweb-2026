<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\PopupAdResource\Pages;
use App\Models\PopupAd;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class PopupAdResource extends Resource
{
    protected static ?string $model = PopupAd::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'YouxTopUp';
    public static function form(Form $form): Form { return $form->schema([Forms\Components\TextInput::make('title')->required(),
Forms\Components\FileUpload::make('image')->image()->directory('popups'),
Forms\Components\TextInput::make('url'),
Forms\Components\Toggle::make('is_active')->default(true),]); }
    public static function table(Table $table): Table { return $table->columns([Tables\Columns\ImageColumn::make('image'),
Tables\Columns\TextColumn::make('title'),
Tables\Columns\IconColumn::make('is_active')->boolean(),])->filters([])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]); }
    public static function getPages(): array { return ['index'=>Pages\ListPopupAds::route('/'), 'create'=>Pages\CreatePopupAd::route('/create'), 'edit'=>Pages\EditPopupAd::route('/{record}/edit')]; }
}
