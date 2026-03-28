<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignTicketRequest extends FormRequest
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
            'agent_uuid' => [
                'required',
                'string',
                Rule::exists('users', 'uuid')->where('customer_id', $user->customer_id),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'agent_uuid.required' => 'O agente é obrigatório.',
            'agent_uuid.exists' => 'Agente inválido ou não pertence à sua empresa.',
        ];
    }
}
