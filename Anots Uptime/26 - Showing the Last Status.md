1 - docker-compose run --rm artisan make:resource CheckResource

chamo no endpointResource 

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\EndpointFrequency;
use App\Http\Resources\CheckResource;

class EndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
        public function toArray(Request $request): array
        {
            return [
                'id' => $this->id,
                'location' => $this->location,
                'frequency_label' => EndpointFrequency::from($this->frequency)->label(),

                'frequency_value' => EndpointFrequency::from($this->frequency)->value,
                
                'lastest_check' => CheckResource::make($this->check), // $this->check vem no relacionamento endpoint

<!-- e o CheckResource -->
class CheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'response_code' => $this->response_code,
            'response_body' => $this->response_body,

        ];
    }
}

<!-- no dashboard.vue testamos  se o lastest_check está sendo retornado-->

 {{endpoints.data }}


 <!-- 3 na model Check.php -->
 
 public function isSuccessful()
 {
     return $this->response_code >= 200 && $this->response_code < 300;
 }
 
 <!-- 4 no CheckResource -->
 
 public function toArray(Request $request): array
 {
     return [
         'id' => $this->id,
         'response_code' => $this->response_code,
         'response_body' => $this->response_body,
         'is_successful' => $this->isSuccessful(),
     ];
 }


<!-- agora no endpoint.vue -->

   <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <template v-if="endpoint.lastest_check">
                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium " :class="{
                    'bg-green-100 text-green-800': endpoint.lastest_check.is_successful,
                    'bg-red-100 text-red-800': !endpoint.lastest_check.is_successful,
                }">
                    {{ endpoint.lastest_check.response_code }}
                </span> 
            </template>
            <template v-else>
                No checks yet
            </template>
        </td>


<!-- 5 no vendor symfony tem os status do response_code -->
vamos adicionar um metodo na model Check.php para retornar o texto do status -->

use Symfony\Component\HttpFoundation\Response;

```php
public function statusText()
{
    return Response::$statusTexts[$this->response_code] ?? 'Unknown';
}
```


<!-- 6 no CheckResource -->
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'response_code' => $this->response_code,
        'response_body' => $this->response_body,
        'is_successful' => $this->isSuccessful(),
        'status_text' => $this->statusText(),  aquii

    ];
}


<!-- 7 no endpoint.vue -->
{{ endpoint.lastest_check.status_text }}

<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <template v-if="endpoint.lastest_check">
                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium " :class="{
                    'bg-green-100 text-green-800': endpoint.lastest_check.is_successful,
                    'bg-red-100 text-red-800': !endpoint.lastest_check.is_successful,
                }">
                    {{ endpoint.lastest_check.response_code }} - {{ endpoint.lastest_check.status_text }}
                </span> 
            </template>
            <template v-else>
                No checks yet
            </template>
        </td>

<!-- vamos no CheckResource criar a logica de formatar a data -->
docker-compose run --rm artisan make:resource DateTimeResource para chamar no  CheckResource

     return [
            'id' => $this->id,
            'response_code' => $this->response_code,
            'response_body' => $this->response_body,
            'is_successful' => $this->isSuccessful(),
            'status_text' => $this->statusText(),
            'created_at' => new DateTimeResource($this->created_at),
        ];
    }
}



<!-- dentro DateTimeResource -->
 public function toArray(Request $request): array
    {
        return [
            'human' => $this->diffForHumans(),
            'date_time' => $this->toDateTimeString(),
        ];
    }
}


<!-- 8 vamos no endpoint.vue -->
<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
           <template v-if="endpoint.lastest_check">
            <time :datetime="endpoint.lastest_check.created_at.date_time" :title="endpoint.lastest_check.created_at.date_time">
                 {{ endpoint.lastest_check.created_at.human }} 
            </time>
           </template> 
           <template v-else>
             -
           </template>
        </td>