<template>
    <VDropdown distance="10">
        
        <button class="flex items-center space-x-2 text-sm">
               <span class="flex items-center gap-1 cursor-pointer">
    <!-- Texto com gradiente -->
    <span class="text-md font-bold font-medium text-white">
        Seleccione o site
    </span>

    <!-- Seta azul (aproximada) -->
    <svg
        class="w-3 h-3 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="1.5"
    >
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m19.5 8.25l-7.5 7.5-7.5-7.5" />
    </svg>
</span>

        </button>

        <template #popper="{hide}" >
          <ul>
   <!--  <li v-for="site in sites.data" :key="site.id">
        <Link
            :href="`/dashboard/${site.id}`"
            class=" text-center px-4 py-3 block text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md"
        >
           {{ site.domain }}  <hr>
        </Link>
    </li> -->

    <li v-for="site in sites.data" :key="site.id">
    <Link
        :href="`/dashboard/${site.id}`"
        class="group flex items-center justify-between p-4 m-2 rounded-lg border border-gray-200 bg-white
               text-sm text-gray-700 hover:bg-indigo-50 hover:border-indigo-300
               hover:shadow-sm transition-all duration-200 "
    >
        <!-- Texto -->
        <div class="flex flex-col">
            <span class="font-medium group-hover:text-indigo-700">
                {{ site.domain }}
            </span>
            <span class="text-xs text-gray-400">
                Monitoramento ativo
            </span>
        </div>

        <!-- Ícone seta -->
        <svg
            class="h-4 w-4 text-gray-400 group-hover:text-indigo-600 transition"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5l7 7-7 7" />
        </svg>
    </Link>
</li>


    <li class="p-2">
        <PrimaryButton
            @click="showNewSiteModal = true; hide()"
           
        >
         <PlusIcon class="h-4 w-4" color="white" />
            Adicionar website
        </PrimaryButton>
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
                 id="domain" type="text"  class="block w-full h-9 text-sm text-gray-700" placeholder="ex: https://meusite.com"
                 :class="{ 'border-red-500': siteForm.errors.domain }"
                 />


                 <InputError class="mt-2" :message="siteForm.errors.domain" />
                <PrimaryButton class="flex items-center gap-2" :disabled="siteForm.processing">
    <template v-if="siteForm.processing">
        <!-- Loading icon -->
        <svg
            class="h-4 w-4 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            />
        </svg>
        <span>Criando...</span>
    </template>

    <template v-else>
        <PlusIcon class="h-4 w-4" />
        <span>Adicionar</span>
    </template>
</PrimaryButton>


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
    import { PlusIcon } from '@heroicons/vue/24/solid'
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