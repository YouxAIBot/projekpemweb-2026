<?php
namespace App\Filament\Admin\Resources;
use App\Filament\Admin\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms; use Filament\Forms\Form; use Filament\Resources\Resource; use Filament\Tables; use Filament\Tables\Table;
class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'YouxTopUp';
    public static function form(Form $form): Form { return $form->schema([Forms\Components\TextInput::make('ticket_number')->disabled(),
Forms\Components\TextInput::make('topic')->required(),
Forms\Components\TextInput::make('type')->required(),
Forms\Components\TextInput::make('name')->required(),
Forms\Components\TextInput::make('whatsapp')->required(),
Forms\Components\TextInput::make('email')->email(),
Forms\Components\Textarea::make('message')->columnSpanFull(),
Forms\Components\Select::make('status')->options(['open'=>'Open','process'=>'Process','closed'=>'Closed'])->default('open'),
Forms\Components\Textarea::make('admin_note')->columnSpanFull(),]); }
    public static function table(Table $table): Table { return $table->columns([Tables\Columns\TextColumn::make('ticket_number')->searchable(),
Tables\Columns\TextColumn::make('topic'),
Tables\Columns\TextColumn::make('name'),
Tables\Columns\TextColumn::make('status')->badge(),
Tables\Columns\TextColumn::make('created_at')->dateTime(),])->filters([])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]); }
    public static function getPages(): array { return ['index'=>Pages\ListSupportTickets::route('/'), 'create'=>Pages\CreateSupportTicket::route('/create'), 'edit'=>Pages\EditSupportTicket::route('/{record}/edit')]; }
}
