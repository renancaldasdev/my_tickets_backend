<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBusinessUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('manager') && $user->customer_id !== null;
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_units')->where(function ($query) use ($user) {
                    return $query->where('customer_id', $user->customer_id);
                }),
            ],

            'slug' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Já existe uma Unidade de Negócio com este nome/slug no sistema.',
        ];
    }
}
