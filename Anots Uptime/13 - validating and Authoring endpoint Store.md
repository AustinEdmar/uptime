1 - docker-compose run --rm artisan make:request EndpointStoreRequest

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
        return true;
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
            'frequency' => ['required', new Enum(EndpointFrequency::class)] // vai validar consoante ao enum EndpointFrequency
        ];
    }
}


<!-- 2 no controller -->

 public function __invoke(EndpointStoreRequest $request, Site $site)
    {

        $site->endpoints()->create($request->only(['location', 'frequency']));

        return back();
        
    }

<!-- dashboard.vue -->

  <TextInput id="location" type="text" class="block w-full h-9 text-sm" placeholder="ex: /productos" 
                       v-model="endpointForm.location"
                       />

                       <InputError :message="endpointForm.errors.location" class="mt-2" />
                       
                    </div> 
<!-- agora vamos criar a docker-compose run --rm artisan make:policy SitePolicy    -->

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Site;

class SitePolicy
{
    



 // agora assim ja registra
    public function storeEndpoint(User $user, Site $site)
    {
        
        return $user->id === $site->user_id;
    }
}


<!-- 3 vou no request -->

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


    