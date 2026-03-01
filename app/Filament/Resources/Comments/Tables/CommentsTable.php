<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commentable_type')
                    ->label(false)
                    ->formatStateUsing(function ($record) {
                        // Отримуємо саму модель (Product, Post тощо) через зв'язок
                        $model = $record->commentable;

                        // Повертаємо назву товару або заголовок статті
                        // Використовуємо ?. (null-safe), якщо об'єкт раптом видалено
                        return $model?->name ?? $model?->title ?? 'Обʼєкт видалено';
                    })
                    ->description(fn($record): string => match ($record->commentable_type) {
                        'App\Models\Product' => 'Товар (ID: ' . $record->commentable_id . ')',
                        'App\Models\Post' => 'Стаття (ID: ' . $record->commentable_id . ')',
                        default => 'Тип: ' . $record->commentable_type,
                    })
                    ->searchable()
                    ->color('primary')
                    ->weight('bold'),

                TextColumn::make('author_name')
                    ->label('Коментатор')
                    ->searchable()
                    ->default('Гість')
                    // Додаємо іконку перед ім'ям, якщо це відповідь
                    ->icon(fn($record) => $record->parent_id ? 'heroicon-m-arrow-uturn-left' : 'heroicon-m-chat-bubble-left-right')
                    // Змінюємо колір іконки для відповідей
                    ->iconColor(fn($record) => $record->parent_id ? 'warning' : 'success')
                    // Додаємо текстове пояснення під ім'ям
                    ->description(fn($record) => $record->parent_id ? "Відповідь на #$record->parent_id" : null),

                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Залишено'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('reply')
                    ->label(false) // Приховуємо текст на самій кнопці в таблиці
                    ->tooltip('Відповісти') // Додаємо підказку при наведенні
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->color('success')
                    ->iconButton() // Робимо кнопку в таблиці компактною іконкою

                    // Налаштування модального вікна
                    ->modalWidth('xl')
                    ->modalHeading(fn(Comment $record) => "Відповідь для {$record->author_name}")

                    // ПРАВИЛЬНІ НАЗВИ КНОПОК ЗГІДНО З ДОКУМЕНТАЦІЄЮ
                    ->modalSubmitActionLabel('Надіслати відповідь')
                    ->modalCancelActionLabel('Скасувати')

                    // Вміст форми модалки
                    ->form([
                        Placeholder::make('original_comment')
                            ->label('Коментар відвідувача:')
                            ->content(fn(Comment $record) => new HtmlString(
                                '<div class="text-sm p-3 rounded-lg bg-gray-50 border-l-4 border-gray-300 italic">'
                                    . e($record->body) .
                                    '</div>'
                            )),

                        Textarea::make('body')
                            ->label('Ваша відповідь')
                            ->placeholder('Введіть текст вашої відповіді...')
                            ->required()
                            ->rows(5)
                            ->autofocus(),
                    ])

                    // Логіка збереження
                    ->action(function (Comment $record, array $data): void {
                        Comment::create([
                            'parent_id' => $record->id,
                            'commentable_id' => $record->commentable_id,
                            'commentable_type' => $record->commentable_type,
                            'body' => $data['body'],
                            'ip_address' => request()->ip(),
                            'user_id' => auth()->id(),
                        ]);
                    })
                    ->successNotificationTitle('Відповідь успішно надіслана!')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
