<?php
namespace App\Filament\Admin\Resources\PopupAdResource\Pages;
use App\Filament\Admin\Resources\PopupAdResource; use Filament\Resources\Pages\EditRecord;
class EditPopupAd extends EditRecord { protected static string $resource = PopupAdResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\DeleteAction::make()]; } }
