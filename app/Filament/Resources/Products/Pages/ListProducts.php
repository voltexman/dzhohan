<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $model = static::getResource()::getModel();

        return [
            'all' => Tab::make('Всі')
                ->badge($model::count())
                ->label('Всі'),

            'in_stock' => Tab::make('В наявності')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quantity', '>', 0))
                ->icon('heroicon-m-check-circle')
                ->badge($model::where('quantity', '>', 0)->count())
                ->badgeColor('success'),

            'out_of_stock' => Tab::make('Продані')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quantity', '<=', 0))
                ->icon('heroicon-m-x-circle')
                ->badge($model::where('quantity', '<=', 0)->count())
                ->badgeColor('gray'),
        ];
    }
}
