<?php

namespace App\Filament\Resources\Attributes\Pages;

use App\Enums\ProductCategory;
use App\Filament\Resources\Attributes\AttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListAttributes extends ListRecords
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->slideOver()
                ->modalWidth('xl')
                ->closeModalByClickingAway(false)
                ->modalHeading(fn() => "Створення нового параметра"),
        ];
    }

    public function getTabs(): array
    {
        return [
            'knife' => Tab::make('Ножі')
                ->label('Для ножів')
                ->modifyQueryUsing(fn($query) => $query->where('group', ProductCategory::KNIFE))
                ->badgeColor('primary'),

            'material' => Tab::make('Матеріали')
                ->label('Для матеріалів')
                ->modifyQueryUsing(fn($query) => $query->where('group', ProductCategory::MATERIAL))
                ->badgeColor('warning'),
        ];
    }
}
