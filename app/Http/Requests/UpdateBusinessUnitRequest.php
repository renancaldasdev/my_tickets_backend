<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('manager') && $user->customer_id !== null;
    }

    public function rules(): array
    {
        $user = $this->user();
        $businessUnitSlug = $this->route('slug');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('business_units', 'name')->where(function ($query) use ($user) {
                    return $query->where('customer_id', $user->customer_id);
                })->ignore($businessUnitSlug, 'slug'),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Já existe uma Unidade de Negócio com este nome. Escolha outro.',
        ];
    }
}
