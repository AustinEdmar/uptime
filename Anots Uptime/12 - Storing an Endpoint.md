1 - docker-compose run --rm artisan make:controller EndpointStoreController

Route::post('/endpoints', EndpointStoreController::class)->middleware(['auth', 'verified']);


<!-- 2
 public function __invoke()
    {

        dd("works");
        
    }
    
     -->

<!-- 3 -->


dashboard.vue
const page = usePage();

const endpointForm = useForm({
    location: null,
    frequency: page.props.endpointFrequencies.data[0].frequency,
});

const storeEndpoint = () => {
    endpointForm.post('/endpoints', {
        preserveScroll: true,
    });
}


<!-- 4 - dashboard.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SiteSelector from '@/Components/SiteSelector.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import {useForm, usePage} from '@inertiajs/vue3';

const props = defineProps({
    site: Object,
    sites: Object
    
});


const page = usePage();

const endpointForm = useForm({
    location: null,
    frequency: page.props.endpointFrequencies.data[0].frequency,
});

const storeEndpoint = () => {
    endpointForm.post(`/sites/${props.site.data.id}/endpoints`, {
        preserveScroll: true,
    });
}  


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
              

                
                <form v-on:submit.prevent="storeEndpoint" class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start p-3 mt-4 space-x-2">
                     <!-- os input -->
                    <div class="grow">
                       <InputLabel for="location" value="Location" class="sr-only" />
                       <TextInput id="location" type="text" class="block w-full h-9 text-sm" placeholder="ex: /productos" 
                       v-model="endpointForm.location"
                       />
                    </div> 


                    <!-- frequency -->
                    <div>
                        <InputLabel for="frequency" value="Frequency" class="sr-only" />
                        <select name="frequency" id="frequency" 
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                        v-model="endpointForm.frequency">
                            <option :value="frequency.frequency" v-for="frequency in page.props.endpointFrequencies.data" :key="frequency.frequency">
                                {{ frequency.label }}
                            </option> 
                            
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

<!-- rota
Route::post('/sites/{site}/endpoints', EndpointStoreController::class)->middleware(['auth', 'verified']);
 -->


<!-- 5 - EndpointStoreController.php -->
class EndpointStoreController extends Controller
{

    public function __invoke(Request $request, Site $site)
    {

        $site->endpoints()->create($request->only(['location', 'frequency']));

        return back();
        
    }
    
}

<!-- vamos criar o Observer para o nextcheck -->

docker-compose run --rm artisan make:observer EndpointObserver


namespace App\Observers;

use App\Models\Endpoint;

class EndpointObserver
{
    
    public function creating(Endpoint $endpoint)
    {
        $endpoint->next_check = now()->addSeconds($endpoint->frequency);
    }
}


<!-- 6 - AppServiceProvider.php para registrar o observer -->
<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Site;
use App\Observers\EndpointObserver;
use App\Observers\SiteObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
        Endpoint::observe(EndpointObserver::class);
    }
}
