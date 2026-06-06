<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\GameResource\Pages;
use App\Models\Game;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class GameResource extends Resource
{
    protected static ?string $model = Game::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'YouxTopUp';
    public static function form(Form $form): Form { return $form->schema([Forms\Components\TextInput::make('name')->required()->maxLength(255),
Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord:true)->maxLength(255),
Forms\Components\TextInput::make('short_description')->maxLength(255),
Forms\Components\Select::make('type')->options(['uid'=>'Via UID','login'=>'Via Login'])->required(),
Forms\Components\FileUpload::make('image')->image()->directory('games'),
Forms\Components\FileUpload::make('banner_image')->image()->directory('games/banners'),
Forms\Components\Toggle::make('is_popular'),
Forms\Components\Toggle::make('is_active')->default(true),
Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
Forms\Components\RichEditor::make('description')->columnSpanFull(),]); }
    public static function table(Table $table): Table { return $table->columns([Tables\Columns\ImageColumn::make('image'),
Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
Tables\Columns\TextColumn::make('type')->badge(),
Tables\Columns\IconColumn::make('is_active')->boolean(),])->filters([])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]); }
    public static function getPages(): array { return ['index'=>Pages\ListGames::route('/'), 'create'=>Pages\CreateGame::route('/create'), 'edit'=>Pages\EditGame::route('/{record}/edit')]; }
}
