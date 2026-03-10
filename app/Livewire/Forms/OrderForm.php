<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OrderForm extends Form
{
    #[
        Validate(
            'required|string|min:2|max:50',
            message: [
                'required' => 'Вкажіть ваше імʼя.',
                'string' => 'Імʼя повинно бути текстом.',
                'min' => 'Імʼя повинно містити щонайменше 2 символи.',
                'max' => 'Імʼя не може перевищувати 50 символів.',
            ],
        ),
    ]
    public string $first_name = '';

    #[
        Validate(
            'required|string|min:2|max:50',
            message: [
                'required' => 'Вкажіть ваше прізвище.',
                'string' => 'Прізвище повинно бути текстом.',
                'min' => 'Прізвище повинно містити щонайменше 2 символи.',
                'max' => 'Прізвище не може перевищувати 50 символів.',
            ],
        ),
    ]
    public string $last_name = '';

    #[
        Validate(
            'required|regex:/^\+?[0-9\s\-\(\)]{10,18}$/',
            message: [
                'required' => 'Вкажіть номер телефону.',
                'regex' => 'Вкажіть коректний номер телефону.',
            ],
        ),
    ]
    public string $phone = '';

    #[
        Validate(
            'required|email:rfc,dns|max:100',
            message: [
                'required' => 'Вкажіть електронну пошту.',
                'email' => 'Вкажіть коректну адресу.',
                'max' => 'Занадто багато символів.',
            ],
        ),
    ]
    public string $email = '';

    #[Validate('required|in:nova_poshta,ukrposhta,courier')]
    public string $delivery_method = 'nova_poshta';

    #[
        Validate(
            'required|string|min:2|max:100',
            message: [
                'required' => 'Вкажіть населений пункт.',
                'min' => 'Назва міста занадто коротка.',
            ],
        ),
    ]
    public string $city = '';

    #[
        Validate(
            'required|string|min:5|max:255',
            message: [
                'required' => 'Вкажіть адресу або номер відділення доставки.',
                'min' => 'Адреса занадто коротка.',
            ],
        ),
    ]
    public string $address = '';

    #[
        Validate(
            'nullable|string|max:1500',
            message: [
                'string' => 'Коментар повинен бути текстом.',
            ],
        ),
    ]
    public string $comment = '';
}
