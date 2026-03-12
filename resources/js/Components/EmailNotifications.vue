<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg rounded-lg p-4">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">Notificações</h2>
           <!-- 4 -->
         <form @submit.prevent="form.post(`/sites/${site.data.id}/notifications/emails`, {preserveScroll: true, onSuccess: () => form.reset()})" class="flex items-start mt-4 space-x-2">
                     <!-- os input -->
                    <div class="grow">
                       <InputLabel for="email" value="Email" class="sr-only" />
                       <!-- 2 -->
                       <TextInput id="email" type="email" class="block w-full h-9 text-sm" placeholder="ex: austin@gmail.com" 
                       v-model="form.email"
                       />

                         <InputError :message="form.errors.email" class="mt-2" />
                       
                    </div> 


                   

                    <PrimaryButton>
                        <PlusIcon />
                        Adicionar
                    </PrimaryButton>
                </form>


                <div class="mt-6">
                    <ul class="space-y-2" v-if="notification_emails && notification_emails.length > 0">
                        <li v-for="email in notification_emails" :key="email" class="flex item-center justify-between">
                         <span class="text-sm">{{ email }}</span>

                         <button @click="router.delete(`/sites/${site.data.id}/notifications/emails`,{
                          data: { email },
                         preserveScroll: true
                            }
                          )"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                         </button>
                        </li>
                    </ul>
                </div>
    </div>
</template>


<script setup>
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import { PlusIcon } from '@heroicons/vue/24/solid'
import { router } from '@inertiajs/vue3'



/* 3 */
const props = defineProps({
    site: Object,
    notification_emails: Array,
});
/* 1 */
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

</script>


















