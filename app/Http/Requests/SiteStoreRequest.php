<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiteStoreRequest extends FormRequest
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
        /* return [
            'domain' => 'required|url'
        ]; */

        return [
        'domain' => [
            'required',
            'url',
            Rule::unique('sites')
                ->where(fn ($q) => $q->where('user_id', $this->user()->id)),
        ],
       ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'O domínio é obrigatório.',
            'domain.url' => 'Informe um domínio válido.',
            'domain.unique' => 'Este domínio já está cadastrado para este usuário.',
        ];
    }
}
