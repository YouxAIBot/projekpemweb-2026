<?php
namespace App\Filament\Admin\Resources\FaqResource\Pages;
use App\Filament\Admin\Resources\FaqResource; use Filament\Resources\Pages\EditRecord;
class EditFaq extends EditRecord { protected static string $resource = FaqResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\DeleteAction::make()]; } }
