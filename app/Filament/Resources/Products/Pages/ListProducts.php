<?php

namespace App\Filament\Resources\Products\Pages;

use App\Enums\ProductCategory;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    // public function getHeading(): string
    // {
    //     return '';
    // }

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
                ->label('Всі товари'),

            'tactical' => Tab::make('Тактичні')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category', ProductCategory::TACTICAL))
                ->badge($model::where('category', ProductCategory::TACTICAL)->count()),

            'kitchen' => Tab::make('Кухонні')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category', ProductCategory::KITCHEN))
                ->badge($model::where('category', ProductCategory::KITCHEN)->count()),

            'hunting' => Tab::make('Мисливські')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category', ProductCategory::HUNTING))
                ->badge($model::where('category', ProductCategory::HUNTING)->count()),

            'edc' => Tab::make('На кожен день')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category', ProductCategory::EDC))
                ->badge($model::where('category', ProductCategory::EDC)->count()),

            'outdoor' => Tab::make('Для походів')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category', ProductCategory::OUTDOOR))
                ->badge($model::where('category', ProductCategory::OUTDOOR)->count()),
        ];
    }
}
