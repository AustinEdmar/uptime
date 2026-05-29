1 - vamos fazer a tela quando clicamos em um location aparece todos os requests daquele location

docker-compose run --rm artisan make:controller EndpointIndexController 
<!-- Route::get('/endpoints/{endpoint}', EndpointIndexController::class)->middleware(['auth', 'verified']);
 -->


2 - vamos criar a tela endpoint index
<!-- 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class EndpointIndexController extends Controller
{
    public function __invoke(Request $request, $endpoint)
    {
       return Inertia::render('Endpoint' /* [
           'endpoint' => $endpoint,
       ] */);
    }
} -->

3 - em components/Endpoint.vue, passamos o id do endpoint para a rota e linkamos para a tela de endpoint index
 <template v-else>
                <Link :href="`/endpoints/${endpoint.id}`" class="text-indigo-600 hover:text-indigo-900">
                   {{ endpoint.location }}
                </Link>
            </template>


4 - Agora adicionei a tela de endpoint index

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <a href="" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    /endpoint
                </h2>
                <div>
                    Site selector
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <h2 class="font-semibold text-lg text-gray-800 leading-tight">Checks</h2>

                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8 mt-3">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full table-fixed divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="min-w-[12rem] pl-4 py-3.5 px-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                Checked at
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Response code
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                Response body
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


5 - em dashboardcontroller removei o sites e coloquei em handleinertia share 
<!--     public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],

            'endpointFrequencies' => EndpointFrequencyResource::collection(EndpointFrequency::cases()), // case por temos um enum 
            'sites' => $request->user() ? SiteResource::collection($request->user()->sites()->latest()->get()) : null,
        ];
    }
}  --> para ter acesso em todas as paginas


6 -  'site' => SiteResource::make($this->site), adicionado no endpointresource para ter acesso ao site do endpoint no nome do navbar, para definir o botao de mostrar o nome e voltar 
<Link :href="`/dashboard/${endpoint.data.site.id}`">{{ endpoint.data.url }}</Link>


<!-- 7 voltamos no endpointResource add o a model Checks para exibir do tela Endpoint do id -->
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\EndpointFrequency;
use App\Http\Resources\CheckResource;
use App\Http\Resources\SiteResource;

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
                
                'url' => $this->url(),
                'site' => SiteResource::make($this->site),
                'checks' => CheckResource::collection($this->checks),############
                
                
            ];
        }
    }



<!-- 8 vou no pages/ndpoint.vue -->
import Check from '@/Components/Check.vue';
</thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                       <Check v-for="check in endpoint.data.checks" :key="check.id" :check="check" />
                                    </tbody>
                                </table>
<!-- 9 vou no components/Check.vue -->

<template>
    <tr>
        <td class="whitespace-nowrap py-4 pl-4 sm:pl-6 px-3 text-sm font-medium text-gray-900">
            {{ check.created_at.date_time }}
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium " :class="{
                    'bg-green-100 text-green-800': check.is_successful,
                    'bg-red-100 text-red-800': !check.is_successful,
                }">
                    {{ check.response_code }} - {{ check.status_text }}
                </span> 
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <template v-if="check.response_body">
                <button>View</button>
            </template>
            <template v-else>
                -
            </template>
        </td>
    </tr>
</template>

<script setup>
defineProps({
    check: Object,
});
</script>




















