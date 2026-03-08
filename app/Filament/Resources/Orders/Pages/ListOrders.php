<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\Order\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

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
                ->label('Всі'),

            'new' => Tab::make('Нові')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Pending))
                ->icon('heroicon-m-sparkles')
                ->badge($model::where('status', OrderStatus::Pending)->count())
                ->badgeColor('danger'),

            'manufacturing' => Tab::make('У виробництві')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Manufacturing))
                ->icon('heroicon-m-wrench-screwdriver')
                ->badge($model::where('status', OrderStatus::Manufacturing)->count())
                ->badgeColor('info'),

            'completed' => Tab::make('Виконані')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Completed))
                ->icon('heroicon-m-check-badge')
                ->badge($model::where('status', OrderStatus::Completed)->count())
                ->badgeColor('success'),

            'cancelled' => Tab::make('Скасовані')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', OrderStatus::Cancelled))
                ->icon('heroicon-m-x-circle')
                ->badge($model::where('status', OrderStatus::Cancelled)->count())
                ->badgeColor('gray'),
        ];
    }
}
