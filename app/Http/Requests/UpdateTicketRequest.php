<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->hasRole(['manager', 'agent', 'dev']);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'subject' => [
                'sometimes',
                'required',
                'string',
                'min:5',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'required',
                'string',
                'min:10',
            ],

            'priority_slug' => [
                'sometimes',
                'required',
                'string',
                Rule::exists('priorities', 'slug'),
            ],

            'category_uuid' => [
                'sometimes',
                'required',
                'string',
                Rule::exists('categories', 'uuid')
                    ->where('customer_id', $user->customer_id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subject.min' => 'O assunto deve ter no mínimo 5 caracteres.',
            'description.min' => 'A descrição deve ter no mínimo 10 caracteres.',
            'priority_slug.exists' => 'Prioridade inválida.',
            'category_uuid.exists' => 'Categoria inválida ou não pertence à sua empresa.',
        ];
    }
}
