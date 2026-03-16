<?php

namespace App\Livewire\Forms;

use App\Enums\Order\DeliveryMethod;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OrderForm extends Form
{
    #[Validate('required|string|min:2|max:50', message: [
        'first_name.required' => 'Вкажіть ваше імʼя.',
        'first_name.min' => 'Імʼя занадто коротке.',
    ])]
    public string $first_name = '';

    #[Validate('required|regex:/^\+?[0-9\s\-\(\)]{10,18}$/', message: [
        'phone.required' => 'Вкажіть номер телефону.',
        'phone.regex' => 'Некоректний формат телефону.',
    ])]
    public string $phone = '';

    #[Validate(['required', new Enum(DeliveryMethod::class)], message: [
        'delivery_method.required' => 'Оберіть спосіб доставки.',
        'delivery_method.enum' => 'Некоректний спосіб доставки.',
    ])]
    public string $delivery_method = 'nova_poshta';

    #[Validate('required_if:delivery_method,nova_poshta,ukr_poshta|nullable|string|min:2|max:50', message: [
        'last_name.required_if' => 'Прізвище обовʼязкове для доставки поштою.',
    ])]
    public string $last_name = '';

    #[Validate('required_if:delivery_method,nova_poshta,ukr_poshta|nullable|email:rfc,dns|max:100', message: [
        'email.required_if' => 'Пошта потрібна для оформлення замовлення.',
        'email.email' => 'Вкажіть дійсну адресу.',
    ])]
    public string $email = '';

    #[Validate('required_unless:delivery_method,pickup|nullable|string|min:2|max:100', message: [
        'city.required_unless' => 'Вкажіть місто для доставки.',
    ])]
    public string $city = '';

    #[Validate('required_unless:delivery_method,pickup|nullable|string|min:5|max:255', message: [
        'address.required_unless' => 'Вкажіть адресу або номер відділення.',
    ])]
    public string $address = '';

    #[Validate('nullable|string|max:1500')]
    public string $comment = '';

    public function updatedFormDeliveryMethod($value)
    {
        $this->resetValidation([
            'form.last_name',
            'form.email',
            'form.city',
            'form.address',
        ]);
    }
}
