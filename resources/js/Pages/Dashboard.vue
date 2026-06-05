<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import PrimaryButton from '../Components/PrimaryButton.vue';
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import Endpoint from '../Components/Endpoint.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/solid'
import EmailNotifications from '../Components/EmailNotifications.vue'
import { onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    site: Object,
    sites: Object,
    endpoints: Object,
    
});


const page = usePage();

const endpointForm = useForm({
    location: null,
    frequency: page.props.endpointFrequencies.data[0].frequency,
});

const storeEndpoint = () => {
    endpointForm.post(`/sites/${props.site.data.id}/endpoints`, {
        preserveScroll: true,
        onSuccess: () => {
            endpointForm.reset();
        }
    });
} 


const deleteSite = () => {
    if (window.confirm("Tens a certeza ?")){
        router.delete(`/sites/${props.site.data.id}`)
    }
}

let pollingInterval = null

const startPolling = () => {
    pollingInterval = setInterval(() => {
        router.reload({
            only: ['endpoints'], // só atualiza endpoints
            preserveScroll: true,
            preserveState: true,
        })
    }, 5000) // 5 segundos
}

const stopPolling = () => {
    if (pollingInterval) {
        clearInterval(pollingInterval)
        pollingInterval = null
    }
}

onMounted(() => {
    startPolling()
})

onUnmounted(() => {
    stopPolling()
})

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :site="site" :sites="sites">


        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

               <template v-if="!site">
    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
        
        <!-- Imagem -->
        <img
            :src="'/storage/caixa.gif'"
            alt="Sem sites"
            class="w-64 h-64 object-contain mb-6 animate-fade-in"
        />

        <!-- Título -->
        <h2 class="text-2xl font-bold text-gray-800">
            Nenhum site criado
        </h2>

        <!-- Texto -->
        <p class="mt-2 text-gray-500 max-w-md">
            Ainda não tens nenhum Endereço criado. Cria o teu primeiro Endereço para Monitorar.
        </p>

       
    </div>
</template>

                 <template v-else>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Endpoint</h2>
              

                
                <form v-on:submit.prevent="storeEndpoint" class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex items-start p-3 mt-4 space-x-2">
                     <!-- os input -->
                    <div class="grow">
                       <InputLabel for="location" value="Location" class="sr-only" />
                       <TextInput id="location" type="text" class="block w-full h-9 text-sm" placeholder="ex: /productos" 
                       v-model="endpointForm.location"
                       />

                       <InputError :message="endpointForm.errors.location" class="mt-2" />
                       
                    </div> 


                    <!-- frequency -->
                    <div>
                        <InputLabel for="frequency" value="Frequency" class="sr-only" />
                        <select name="frequency" id="frequency" 
                        class="border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-9 leading-none text-sm"
                        v-model="endpointForm.frequency">
                            <option :value="frequency.frequency" v-for="frequency in page.props.endpointFrequencies.data" :key="frequency.frequency">
                                {{ frequency.label }}
                            </option> 
                            
                        </select>

                    </div>

                    <PrimaryButton>
                        <PlusIcon class="h-4 w-4" color="white" />
                        
                        Adicionar
                    </PrimaryButton>
                </form>

                 <div class="mt-8 flex flex-col">
                    <h2 class="flex items-center gap-2 font-semibold text-lg text-gray-800 leading-tight">
    
    Actualmente monitorando
    (<span
        class="inline-flex items-center justify-center
               h-6 w-6 rounded-full
               bg-green-500 text-white
               text-md font-bold"
    >
        {{endpoints.data.length }}
    </span>)


</h2>


                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8 mt-3">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full table-fixed divide-y divide-gray-300">
                                    <thead class="bg-gradient-to-r from-[#495AFF] to-[#0ACFFE] text-white shadow-sm">
                                        <tr>
                                            <th scope="col" class="min-w-[12rem] pl-4 py-3.5 px-3 text-left text-sm font-semibold text-white sm:pl-6">
                                                Endereço
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                                Frequência
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                                Último check
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                                Último status
                                            </th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold">
                                                Uptime
                                            </th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                Acções
                                            </th>
                                            
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <Endpoint v-for="endpoint in endpoints.data" :key="endpoint.id" :endpoint="endpoint"/>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                     
                <div class="grid grid-cols-3 gap-12 mt-4">
                   <EmailNotifications :site="site" :notification_emails="site.data.notification_emails" />
                </div>

                 <div class="mt-6">
            <button class="text-red-500 text-sm" v-on:click="deleteSite"> Deletar Site </button>
            </div>
            </template>
            </div>
 
           

        </div> 
    </AuthenticatedLayout>
</template>
