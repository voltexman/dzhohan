<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Enums\Order\OrderType;
use App\Models\Order;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    // Цей метод відповідає за те, чи з'явиться вкладка/блок реляції
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // Показуємо товари лише якщо це тип "Purchase" (Купівля)
        return $ownerRecord instanceof Order && $ownerRecord->type === OrderType::Purchase;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Назва')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->heading('Список товарів')
            ->description('Перелік товарів в замовленні.')
            ->columns([
                TextColumn::make('name')->label('Назва товару'),

                TextColumn::make('qty')->label('Кількість'),

                TextColumn::make('price')
                    ->label('Вартість (за од.)')
                    ->formatStateUsing(fn ($record, $state) => $record->currency->format($state))
                    ->color('gray')
                    ->alignEnd(),

                TextColumn::make('subtotal')
                    ->label('Сума')
                    ->state(fn ($record) => $record->price * $record->qty)
                    ->formatStateUsing(fn ($record, $state) => $record->currency->format($state))
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
