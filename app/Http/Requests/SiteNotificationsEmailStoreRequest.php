<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class SiteNotificationsEmailStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $site = $this->route('site'); // pega o Site da rota
        $existingEmails = $site ? $site->notification_emails ?? [] : [];

        return [
            
            'email' => [
                'required',
                'email',
                'not_in:' . implode(',', $existingEmails),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.not_in' => 'Este email já está cadastrado para este site.',
        ];
    }
}
