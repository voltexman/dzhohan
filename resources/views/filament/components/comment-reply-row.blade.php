<div class="p-6 bg-gray-50/50 border-t border-gray-100">
    <div class="flex flex-col gap-4">
        <div class="flex items-start gap-3">
            <div class="p-2 bg-white rounded-lg border border-gray-200 shadow-sm flex-1">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Оригінальний текст:</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $record->body }}</p>
            </div>
        </div>

        @if ($record->children->count() > 0)
            <div class="ml-10 space-y-3">
                <p class="text-xs font-semibold text-gray-400 uppercase">Попередні відповіді:</p>
                @foreach ($record->children as $child)
                    <div class="p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-blue-800">{{ $child->author_name }}</span>
                            <span class="text-[10px] text-blue-600">{{ $child->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-blue-900">{{ $child->body }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-right">
            <span class="text-xs text-gray-400 italic">Натисніть кнопку "Відповісти" у списку дій, щоб додати нову
                репліку.</span>
        </div>
    </div>
</div>
