<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->withCount('likes')
                    ->orderByRaw('COALESCE(parent_id, id) DESC')
                    ->orderByRaw('parent_id IS NULL DESC')
                    ->orderBy('created_at', 'asc')
            )
            ->groups([
                Group::make('commentable_id')
                    ->label('Обговорення')
                    ->getTitleFromRecordUsing(function ($record) {
                        $model = $record->commentable;
                        $type = $record->commentable_type === 'App\Models\Product' ? 'Товар' : 'Стаття';

                        return "{$type}: ".($model?->name ?? $model?->title ?? 'Видалено');
                    })
                    ->collapsible(),
            ])
            ->defaultGroup('commentable_id')

            ->columns([
                TextColumn::make('author_name')
                    ->label('Коментатор')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?: 'Ім’я не вказано')
                    ->icon(fn ($record) => $record->parent_id ? 'heroicon-m-arrow-uturn-left' : null)
                    ->iconColor('warning')
                    ->extraAttributes(fn ($record) => [
                        // 'class' => $record->parent_id ? 'pl-8 opacity-70' : 'font-bold',
                    ])
                    ->description(
                        fn ($record) => $record->parent_id
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
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->parent_id
                            ? 'text-sm bg-blue-50/50 p-2 rounded-lg border-l-2 border-blue-200'
                            : 'text-sm bg-gray-50 p-2 rounded-lg',
                    ]),

                TextColumn::make('likes_count')
                    ->label('Лайки')
                    ->badge()
                    ->color(fn ($record) => $record->isLiked() ? 'danger' : 'gray')
                    ->icon('heroicon-m-heart')
                    ->alignCenter()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(false)
                    ->width('1%')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Залишено')
                    ->width('15')
                    ->dateTime('d.m.Y')
                    ->weight(FontWeight::Medium)
                    ->description(fn ($record) => $record->created_at->format('H:i'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')

            ->actions([
                // Кнопка тільки для повідомлень користувачів
                Action::make('reply')
                    ->label('Відповісти')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn ($record) => $record->parent_id === null)
                    ->slideOver()
                    ->modalWidth('md')
                    ->form([
                        Placeholder::make('context')
                            ->label('Питання користувача:')
                            ->content(fn ($record) => $record->body),

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
                            'author_name' => $user?->hasRole('admin') ? 'Адміністратор' : ($user?->name ?? 'Модератор'),
                        ]);

                        Notification::make()->title('Відповідь опублікована')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Видалити вибрані')
                        ->modalHeading('Видалити вибрані коментарі?')
                        ->successNotificationTitle('Коментарі видалено'),
                ]),
            ]);
    }
}
