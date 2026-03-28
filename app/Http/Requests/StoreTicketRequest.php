<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'subject' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
            ],

            'priority_slug' => [
                'required',
                'string',
                Rule::exists('priorities', 'slug'),
            ],

            'category_uuid' => [
                'required',
                'string',
                Rule::exists('categories', 'uuid')
                    ->where('customer_id', $user->customer_id),
            ],

            'business_unit_slug' => [
                'required',
                'string',
                Rule::exists('business_units', 'slug')
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
            'subject.required' => 'O assunto do ticket é obrigatório.',
            'subject.min' => 'O assunto deve ter no mínimo 5 caracteres.',
            'description.required' => 'A descrição do ticket é obrigatória.',
            'description.min' => 'A descrição deve ter no mínimo 10 caracteres.',
            'priority_slug.required' => 'A prioridade é obrigatória.',
            'priority_slug.exists' => 'Prioridade inválida.',
            'category_uuid.required' => 'A categoria é obrigatória.',
            'category_uuid.exists' => 'Categoria inválida ou não pertence à sua empresa.',
            'business_unit_slug.required' => 'A Unidade de Negócio é obrigatória.',
            'business_unit_slug.exists' => 'Unidade de Negócio inválida ou não pertence à sua empresa.',
        ];
    }
}
