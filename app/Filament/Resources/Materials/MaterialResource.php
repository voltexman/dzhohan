<?php

namespace App\Filament\Resources\Materials;

use App\Enums\ProductCategory;
use App\Filament\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Resources\Materials\Pages\EditMaterial;
use App\Filament\Resources\Materials\Pages\ListMaterials;
use App\Filament\Resources\Materials\Schemas\MaterialForm;
use App\Filament\Resources\Materials\Tables\MaterialsTable;
use App\Models\Material;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MaterialResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|UnitEnum|null $navigationGroup = 'Магазин';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Товари';

    protected static ?string $pluralModelLabel = 'Матеріали';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('category', ProductCategory::MATERIAL);
    }

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
