<?php
namespace App\Filament\Admin\Resources\PaymentMethodResource\Pages;
use App\Filament\Admin\Resources\PaymentMethodResource; use Filament\Resources\Pages\ListRecords;
class ListPaymentMethods extends ListRecords { protected static string $resource = PaymentMethodResource::class; protected function getHeaderActions(): array { return [\Filament\Actions\CreateAction::make()]; } }
