<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketInteractionRequest extends FormRequest
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
        return [
            'content' => [
                'required',
                'string',
                'min:3',
            ],

            'is_internal' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'O conteúdo da interação é obrigatório.',
            'content.min' => 'A interação deve ter no mínimo 3 caracteres.',
            'is_internal.boolean' => 'O campo is_internal deve ser verdadeiro ou falso.',
        ];
    }
}
