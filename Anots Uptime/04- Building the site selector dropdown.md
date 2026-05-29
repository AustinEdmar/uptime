docker-compose run --rm npm i floating-vue --force

em app.js registr 
import 'floating-vue/dist/style.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import FloatingVue from 'floating-vue';


 .use(FloatingVue)

<!-- 2 criei este component e importe no dashboard -->

<template>
    <VDropdown distance="10">
        
        <button class="flex items-center space-x-2 text-sm">
               <span class="text-gray-500 hover:text-gray-700">Seleccione o Site</span>
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
        </button>

        <template #popper>
             <ul class="-space-y-2">
                <li v-for="site in sites" :key="site.id">
                    <Link :href="`/dashboard/${site.id}`" class="
                    px-4 py-2 hover:bg-gray-100 block text-sm text-gray-500 hover:text-gray-700"
                    >
                        {{ site.domain }}
                    </Link>
                </li>
                


                <li>
                    <button class="block w-full text-left px-4 hover:bg-gray-100 text-indigo-500 font-bold ">Add a website</button>
                </li>

             </ul>
        </template>

    </VDropdown>
</template>


<script setup>

    import { Link } from '@inertiajs/vue3';

    defineProps({
        sites: Array
    });

</script>
           

           <!-- dasboard -->

 <!--<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SiteSelector from '@/Components/SiteSelector.vue';

defineProps({
    site: Object,
    sites: Array
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800" >
                    Dashboard
                </h2>

                <div>
                    <SiteSelector :sites="sites" />
                </div>
            </div>
           
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                       {{ site }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
  -->~

  3 - controller

  <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Site;

class DashboardController extends Controller
{
    public function __invoke(Request $request, Site $site){

        return Inertia::render('Dashboard', [
            'site' => $site,
            'sites' => Site::get(),

        ]);
    }
}