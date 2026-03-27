<?php

namespace App\Filament\Resources\Attributes\Pages;

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
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $model = static::getResource()::getModel();

        return [
            'knife' => Tab::make('Для ножів')
                ->label('Параметри ножа')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'knife'))
                ->badge($model::where('group', 'knife')->count())
                ->badgeColor('primary'),

            'material' => Tab::make('Матеріали')
                ->label('Для матеріалів')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'material'))
                ->badge($model::where('group', 'material')->count())
                ->badgeColor('warning'),

            'order' => Tab::make('Для замовлень')
                ->label('Для замовлень')
                ->modifyQueryUsing(fn ($query) => $query->where('group', 'order'))
                ->badge($model::where('group', 'order')->count())
                ->badgeColor('warning'),
        ];
    }
}
