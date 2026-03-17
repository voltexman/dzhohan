<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
// use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->withCount('likes')
                    ->orderByRaw('COALESCE(parent_id, id) DESC')
                    ->orderBy('created_at', 'asc')
            )


            // 2. ГРУПУВАННЯ за товаром/статтею
            ->groups([
                Group::make('commentable_id')
                    ->label('Обговорення')
                    ->getTitleFromRecordUsing(function ($record) {
                        $model = $record->commentable;
                        $type = $record->commentable_type === 'App\Models\Product' ? 'Товар' : 'Стаття';
                        return "{$type}: " . ($model?->name ?? $model?->title ?? 'Видалено');
                    })
                    ->collapsible(),
            ])
            ->defaultGroup('commentable_id')

            ->columns([
                TextColumn::make('author_name')
                    ->label('Коментатор')
                    ->searchable()
                    ->formatStateUsing(fn($state) => $state ?: 'Ім’я не вказано')
                    // Візуальне дерево: якщо це відповідь (є parent_id), додаємо іконку та відступ
                    ->icon(fn($record) => $record->parent_id ? 'heroicon-m-arrow-uturn-left' : null)
                    ->iconColor('warning')
                    ->extraAttributes(fn($record) => [
                        'class' => $record->parent_id ? 'pl-8 opacity-70' : 'font-bold',
                    ])
                    ->description(
                        fn($record) => $record->parent_id
                            ? 'Ваша відповідь'
                            : ($record->author_email ?? 'Гість')
                    ),
                // ->description(fn($record) => $record->parent_id ? 'Відповідь' : ($record->author_email ?? 'Гість')),

                TextColumn::make('body')
                    ->label('Текст')
                    ->wrap()
                    ->lineClamp(3)
                    ->tooltip(function ($record): ?string {
                        return mb_strlen($record->body) > 100 ? $record->body : null;
                    })
                    // Виділяємо відповіді адміна фоном
                    ->extraAttributes(fn($record) => [
                        'class' => $record->parent_id
                            ? 'text-sm bg-blue-50/50 p-2 rounded-lg border-l-2 border-blue-200'
                            : 'text-sm bg-gray-50 p-2 rounded-lg',
                    ]),

                TextColumn::make('likes_count')
                    ->label('Лайки')
                    ->width('1%')
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'danger' : 'gray')
                    ->icon('heroicon-m-heart')
                    ->alignCenter()
                    ->sortable(),


                ToggleColumn::make('is_active')
                    ->label(false)
                    ->width('1%')
                    ->alignCenter(),

                // ... у списку колонок Tables\Table :: columns

                TextColumn::make('created_at')
                    ->label('Залишено')
                    ->width('15')
                    // Основний текст - тільки дата
                    ->dateTime('d.m.Y')
                    // ->fontFamily('mono') // Опційно: моноширинний шрифт для дати виглядає охайно
                    // ->color('gray')
                    ->weight(FontWeight::Medium)
                    // Підпис під датою - тільки час
                    ->description(fn($record) => $record->created_at->format('H:i'))
                    ->sortable(),
            ])
            // Сортування: спочатку за групою (товаром), потім за часом (щоб діалог йшов по черзі)
            ->defaultSort('created_at', 'asc')

            ->actions([
                // Кнопка тільки для повідомлень користувачів
                Action::make('reply')
                    ->label('Відповісти')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn($record) => $record->parent_id === null)
                    ->slideOver()
                    ->modalWidth('md')
                    ->form([
                        Placeholder::make('context')
                            ->label('Питання користувача:')
                            ->content(fn($record) => $record->body),
                        Textarea::make('body')
                            ->label('Ваша відповідь')
                            ->required()
                            ->rows(8),
                    ])
                    ->action(function (Comment $record, array $data): void {
                        $user = auth()->user();

                        $record->replies()->create([
                            'commentable_id' => $record->commentable_id,
                            'commentable_type' => $record->commentable_type,
                            'body' => $data['body'],
                            'ip_address' => request()->ip(),
                            'user_id' => $user?->id,
                            // Логіка ролі Spatie
                            'author_name' => $user?->hasRole('admin') ? 'Адміністратор' : ($user?->name ?? 'Модератор'),
                        ]);

                        Notification::make()->title('Відповідь опублікована')->success()->send();
                    }),

                // DeleteAction::make()->iconButton(),
            ]);
    }
}
