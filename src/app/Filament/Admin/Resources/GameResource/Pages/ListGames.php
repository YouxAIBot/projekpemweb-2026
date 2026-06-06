<?php
namespace App\Filament\Admin\Resources\GameResource\Pages;
use App\Filament\Admin\Resources\GameResource; use Filament\Resources\Pages\ListRecords;
class ListGames extends ListRecords { protected static string $resource = GameResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } }
