<?php

namespace App\Filament\Resources\Attributes;

use App\Filament\Resources\Attributes\Pages\ListAttributes;
use App\Filament\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static string|UnitEnum|null $navigationGroup = 'Магазин';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Характеристика';

    protected static ?string $pluralModelLabel = 'Характеристики';

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributes::route('/'),
        ];
    }
}
