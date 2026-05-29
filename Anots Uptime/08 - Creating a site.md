

1- final 
<template>
    <VDropdown distance="10">
        
        <button class="flex items-center space-x-2 text-sm">
               <span class="text-gray-500 hover:text-gray-700">Seleccione o Site</span>
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
        </button>

        <template #popper="{hide}" >
          <ul>
    <li v-for="site in sites.data" :key="site.id">
        <Link
            :href="`/dashboard/${site.id}`"
            class="px-4 py-3 block text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md"
        >
            {{ site.domain }}
        </Link>
    </li>

    <li class="pt-2">
        <button
            @click="showNewSiteModal = true; hide()"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-indigo-500 font-bold rounded-md"
        >
            Adicionar um website
        </button>
    </li>
</ul>

        </template>

    </VDropdown>



    <VueFinalModal v-model="showNewSiteModal" classes="flex justify-center items-start pt-16 md:pt-24 mx-4"
    content-class="relative max-h-full rounded bg-white w-full max-w-2xl p-4 md:p-6"
    overlay-class="bg-gradient-to-r from-gray-500 opacity-50" :esc-to-close="true"
    >
    
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">Criar novo site</h2>

            <form v-on:submit.prevent="createSite" class="overflow-hidden space-y-4">

                <InputLabel for="domain" value="Domínio" class="sr-only" />

                <TextInput
                 v-model="siteForm.domain"
                 id="domain" type="text"  class="block w-full h-9 text-sm" placeholder="ex: meusite.com"/>

                <PrimaryButton>Adicionar</PrimaryButton>

            </form>
        
    </VueFinalModal>
</template>


<script setup>

    import { Link } from '@inertiajs/vue3';
    import { VueFinalModal } from 'vue-final-modal';
    import { ref } from 'vue';
    import TextInput from './TextInput.vue';
    import InputLabel from './InputLabel.vue';
    import PrimaryButton from './PrimaryButton.vue';

    import { useForm } from '@inertiajs/vue3';

    const showNewSiteModal = ref(false);

    const siteForm = useForm({
        domain: null,
    });

    const createSite = () => {
        siteForm.post('/sites', {
            preserveScroll: true,
            onSuccess: () => {
                siteForm.reset();
                showNewSiteModal.value = false;
            }
        });
    };

    defineProps({
        sites: Array
    });

</script>


 2-
    route web.php
    Route::post('/sites', SiteStoreController::class);


3 - 
class SiteStoreController extends Controller
{
   
    
    public function __invoke(Request $request)
    {
        $site = $request->user()->sites()->create($request->only(['domain']));
        
        return redirect()->route('dashboard', $site);
    }
}



4 - obsever

// funcao creating, antes de criar o site
 public function creating(Site $site)
    {
        // pegando o scheme e o host do domínio
        $parsed = parse_url($site->domain);
          // atribuindo o scheme e o host ao site
        $site->scheme = Arr::get($parsed, 'scheme');
        $site->domain = Arr::get($parsed, 'host');

        
    }



5 - request


docker-compose run --rm artisan make:request SiteStoreRequest 

true 
return [
    'domain' => 'required|url'
];


6 - controller

public function __invoke(SiteStoreRequest $request)
    {
        $site = $request->user()->sites()->create($request->only(['domain']));
        
        return redirect()->route('dashboard', $site);
    }


<!-- 7 parte final a pegar os erros do formulário -->
                 <InputError class="mt-2" :message="siteForm.errors.domain" />


                 <!-- <template>
    <VDropdown distance="10">
        
        <button class="flex items-center space-x-2 text-sm">
               <span class="text-gray-500 hover:text-gray-700">Seleccione o Site</span>
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
        </button>

        <template #popper="{hide}" >
          <ul>
    <li v-for="site in sites.data" :key="site.id">
        <Link
            :href="`/dashboard/${site.id}`"
            class="px-4 py-3 block text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md"
        >
            {{ site.domain }}
        </Link>
    </li>

    <li class="pt-2">
        <button
            @click="showNewSiteModal = true; hide()"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-indigo-500 font-bold rounded-md"
        >
            Adicionar um website
        </button>
    </li>
</ul>

        </template>

    </VDropdown>



    <VueFinalModal v-model="showNewSiteModal" classes="flex justify-center items-start pt-16 md:pt-24 mx-4"
    content-class="relative max-h-full rounded bg-white w-full max-w-2xl p-4 md:p-6"
    overlay-class="bg-gradient-to-r from-gray-500 opacity-50" :esc-to-close="true"
    >
    
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">Criar novo site</h2>

            <form v-on:submit.prevent="createSite" class="overflow-hidden space-y-4">

                <InputLabel for="domain" value="Domínio" class="sr-only" />

                <TextInput
                 v-model="siteForm.domain"
                 id="domain" type="text"  class="block w-full h-9 text-sm" placeholder="ex: meusite.com"
                 :class="{ 'border-red-500': siteForm.errors.domain }"
                 />


                 <InputError class="mt-2" :message="siteForm.errors.domain" />
                <PrimaryButton class="">Adicionar</PrimaryButton>

            </form>
        
    </VueFinalModal>
</template>


<script setup>

    import { Link } from '@inertiajs/vue3';
    import { VueFinalModal } from 'vue-final-modal';
    import { ref } from 'vue';
    import TextInput from './TextInput.vue';
    import InputLabel from './InputLabel.vue';
    import PrimaryButton from './PrimaryButton.vue';
    import InputError from './InputError.vue';

    import { useForm } from '@inertiajs/vue3';

    const showNewSiteModal = ref(false);

    const siteForm = useForm({
        domain: null,
    });

    const createSite = () => {
        siteForm.post('/sites', {
            preserveScroll: true,
            onSuccess: () => {
                siteForm.reset();
                showNewSiteModal.value = false;
            }
        });
    };

    defineProps({
        sites: Array
    });

</script> -->