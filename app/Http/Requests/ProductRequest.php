<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'price' => ['numeric', 'min:0'],
            'quantity_available' => ['integer', 'min:0'],
        ];

        if ($this->isMethod('post')) {
            $rules['name'][] = 'required';
            $rules['price'][] = 'required';
            $rules['quantity_available'][] = 'required';
        } else {
            $rules['name'] = array_merge(['sometimes', 'required'], $rules['name']);
            $rules['price'] = array_merge(['sometimes', 'required'], $rules['price']);
            $rules['quantity_available'] = array_merge(['sometimes', 'required'], $rules['quantity_available']);
        }

        return $rules;
    }
}
