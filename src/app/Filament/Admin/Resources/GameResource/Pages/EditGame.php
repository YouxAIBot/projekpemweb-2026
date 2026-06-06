<?php
namespace App\Filament\Admin\Resources\GameResource\Pages;
use App\Filament\Admin\Resources\GameResource; use Filament\Resources\Pages\EditRecord;
class EditGame extends EditRecord { protected static string $resource = GameResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\DeleteAction::make()]; } }
