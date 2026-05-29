1 - modifiei o dashboard

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SiteSelector from '@/Components/SiteSelector.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';

defineProps({
    site: Object,
    sites: Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800" >
                    {{ site.data.domain }}
                </h2>

                <div>
                    <SiteSelector :sites="sites" />
                </div>
            </div>
           
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Endpoint</h2>

                <form class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start p-3 mt-4 space-x-2">
                     <!-- os input -->
                    <div class="grow">
                       <InputLabel for="location" value="Location" class="sr-only" />
                       <TextInput id="location" type="text" class="block w-full h-9 text-sm" placeholder="ex: /productos" />
                    </div> 


                    <!-- frequency -->
                    <div>
                        <InputLabel for="frequency" value="Frequency" class="sr-only" />
                        <select name="frequency" id="frequency" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm">
                            <option value="">1 minuto</option>
                            
                        </select>

                    </div>

                    <PrimaryButton>
                        Adicionar
                    </PrimaryButton>
                </form>
            </div>
        </div> 
    </AuthenticatedLayout>
</template>


<!-- 2 vamos no controller dashboard add o frequency -->
   return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            'sites' => SiteResource::collection(Site::get()),
            'endpointFrequencies' => EndpointFrequencyResource::collection(EndpointFrequency::cases()), // case por temos um enum 
            
        ]);
    }
<!-- 3 vamos no model endpoint add o frequency -->
<!-- 4 vamos no resource endpoint add o frequency -->
docker-compose run --rm artisan make:resource EndpointFrequencyResource
 */
    public function toArray(Request $request): array
    {
        return [
            'frequency' => $this->value,
            'label' => $this->label(),
        ];
    }
<!-- 5 vamos no dashboard.vue add o frequency -->
<!-- adicionei label no enum e vou no resource endpoint add o label -->

namespace App\Enums;

enum EndpointFrequency: int
{
    case ONE_MINUTE = 1 * 60;

    case FIVE_MINUTES = 5 * 60;

    case THIRTY_MINUTES = 30 * 60;

    case ONE_HOUR = 60 * 60;

    public function label(): string
    {
        return match ($this) {
            self::ONE_MINUTE => '1 minuto',
            self::FIVE_MINUTES => '5 minutos',
            self::THIRTY_MINUTES => '30 minutos',
            self::ONE_HOUR => '1 hora',
        };
    }
    
}


<!-- dashboard.vue

 <InputLabel for="frequency" value="Frequency" class="sr-only" />
                        <select name="frequency" id="frequency" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm">
                            <option :value="frequency.frequency" v-for="frequency in endpointFrequencies.data" :key="frequency.frequency">
                                {{ frequency.label }}
                            </option>
                            
                        </select> -->

<!-- 6 uma modificação no dashboard controller  -->

<!-- retirei  EndpointFrequencyResource coloquei em share do middleware do inertia  
 return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            'sites' => SiteResource::collection(Site::get())
        ]); -->


<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\EndpointFrequencyResource;
use App\Enums\EndpointFrequency;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],

            'endpointFrequencies' => EndpointFrequencyResource::collection(EndpointFrequency::cases()), // case por temos um enum 
        ];
    }
}
