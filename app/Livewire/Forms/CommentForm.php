<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Attributes\Session;
use Livewire\Form;

class CommentForm extends Form
{
    #[Session(key: 'commentator-name')]
    #[
        Validate(
            'nullable|string|min:2|max:80',
            message: ['min' => 'Занадто мало символів'],
        ),
    ]
    public string $author_name = '';

    #[
        Validate(
            'required|string|min:3|max:500',
            message: [
                'required' => 'Напишіть коментар',
                'min' => 'Коментар занадто короткий',
            ],
        ),
    ]
    public string $body = '';

    #[Validate(
        'nullable|string|min:3|max:500',
        message: [
            // 'required' => 'Напишіть коментар',
            'min' => 'Коментар занадто короткий',
        ],
    )]
    public string $replyBody = '';
}
