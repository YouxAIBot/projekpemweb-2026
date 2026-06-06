<?php
namespace App\Filament\Admin\Resources\PopupAdResource\Pages;
use App\Filament\Admin\Resources\PopupAdResource; use Filament\Resources\Pages\ListRecords;
class ListPopupAds extends ListRecords { protected static string $resource = PopupAdResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } }
