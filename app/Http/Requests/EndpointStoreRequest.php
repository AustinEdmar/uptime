<?php

namespace App\Http\Requests;

use App\Enums\EndpointFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class EndpointStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         /** @var Site $site */
        $site = $this->route('site');

        return $this->user()->can('storeEndpoint', $site);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location' => 'required',
            'frequency' => ['required', new Enum(EndpointFrequency::class)]
        ];
    }

    public function messages(): array
{
    return [
        'location.required' => 'O endpoint (location) é obrigatório.',
        'frequency.required' => 'A frequência é obrigatória.',
        'frequency.enum' => 'A frequência selecionada é inválida.',
    ];
}

}
