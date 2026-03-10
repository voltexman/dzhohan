<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\Order\OrderType;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getHeading(): string
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $model = static::getResource()::getModel();

        return [
            'all' => Tab::make('Всі')
                ->badge($model::count()),

            'purchase' => Tab::make('Купівля')
                ->modifyQueryUsing(fn ($query) => $query->where('type', OrderType::Purchase))
                ->icon('heroicon-m-shopping-cart')
                ->badge($model::where('type', OrderType::Purchase)->count())
                ->badgeColor('success'),

            'manufacturing' => Tab::make('Виготовлення')
                ->modifyQueryUsing(fn ($query) => $query->where('type', OrderType::Manufacturing))
                ->icon('heroicon-m-wrench-screwdriver')
                ->badge($model::where('type', OrderType::Manufacturing)->count())
                ->badgeColor('warning'),
        ];
    }
}
