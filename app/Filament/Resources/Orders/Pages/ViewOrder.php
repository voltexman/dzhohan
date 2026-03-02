<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $order = $this->getRecord();

        if ($order->status === OrderStatus::Pending) {
            $order->update(['status' => OrderStatus::Processing]);

            $order->refresh();
        }
    }
}
