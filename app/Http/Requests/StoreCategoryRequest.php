<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domains\Identity\Models\BusinessUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('manager') && $user->customer_id !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $manager = $this->user();

        $bu = BusinessUnit::where('slug', $this->business_unit_slug)
            ->where('customer_id', $manager->customer_id)
            ->first();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) use ($bu) {
                    return $query->where('business_unit_id', $bu?->id);
                }),
            ],
            'description' => ['nullable', 'string', 'max:255'],

            'business_unit_slug' => [
                'required',
                'string',
                Rule::exists('business_units', 'slug')->where(function ($query) use ($manager) {
                    return $query->where('customer_id', $manager->customer_id);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_unit_slug.exists' => 'A Unidade de Negócio informada é inválida ou não pertence à sua empresa.',
            'name.unique' => 'Já existe uma categoria com este nome nesta Unidade de Negócio.',
        ];
    }
}
