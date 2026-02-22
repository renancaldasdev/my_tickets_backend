<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domains\Core\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('manager') && $user->customer_id !== null;
    }

    public function rules(): array
    {
        $manager = $this->user();

        $categoryUuid = $this->route('uuid');

        $category = Category::where('uuid', $categoryUuid)
            ->where('customer_id', $manager->customer_id)
            ->first();

        $buId = $category ? $category->business_unit_id : null;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where(function ($query) use ($buId) {
                    return $query->where('business_unit_id', $buId);
                })->ignore($categoryUuid, 'uuid'),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Já existe uma categoria com este nome nesta Unidade de Negócio.',
        ];
    }
}
