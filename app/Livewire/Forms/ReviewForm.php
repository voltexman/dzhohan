<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ReviewForm extends Form
{
    #[Validate('min:2|max:50', message: [
        'min' => 'Ім’я занадто коротке',
        'max' => 'Ім’я не може бути довшим за 50 символів',
    ])]
    public string $name = '';

    #[Validate('max:100', message: [
        'max' => 'Контакт занадто довгий',
    ])]
    public string $contact = '';

    #[Validate('required|integer|min:1|max:5', message: [
        'min' => 'Оберіть хоча б одну зірку',
        'max' => 'Максимальна оцінка — 5 зірок',
    ])]
    public int $rating = 5;

    #[Validate('required|min:10|max:2000', message: [
        'required' => 'Напишіть ваш відгук',
        'min' => 'Відгук занадто короткий (мінімум 10 символів)',
        'max' => 'Відгук занадто великий',
    ])]
    public string $text = '';
}
