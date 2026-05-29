1 - Vamos no dashboard.vue adicionar ui para email form
import EmailNotifications from '../Components/EmailNotifications.vue'

criei o component EmailNotifications.vue

 <div class="grid grid-cols-3 gap-12 mt-4">
                   <EmailNotifications />
</div>

<!-- 2 - EmailNotifications.vue -->
<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg rounded-lg p-4">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">Notificações</h2>

         <form action="" class="flex items-start mt-4 space-x-2">
                     <!-- os input -->
                    <div class="grow">
                       <InputLabel for="email" value="Email" class="sr-only" />
                       <TextInput id="email" type="email" class="block w-full h-9 text-sm" placeholder="ex: austin@gmail.com" 
                       
                       />

                       <!-- <InputError message="message" class="mt-2" /> -->
                       
                    </div> 


                   

                    <PrimaryButton>
                        <PlusIcon />
                        Adicionar
                    </PrimaryButton>
                </form>
    </div>
</template>


<script setup>
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import { PlusIcon } from '@heroicons/vue/24/solid'

</script>

<!-- 3 - formulario ja esta pronto -->
vamos no routes/web.php e adicionar a rota

Route::post('/sites/{site}/notifications/emails')->middleware(['auth', 'verified']);

<!-- 4 - vamos criar o controller -->
php artisan make:controller SiteNotificationsEmailStoreController
Route::post('/sites/{site}/notifications/emails', SiteNotificationsEmailStoreController::class)->middleware(['auth', 'verified']);

<!-- 5 - vamos criar o post no form EmailNotifications.vue -->
antes no dashboard.vue passo por props o site
<!--  <div class="grid grid-cols-3 gap-12 mt-4">
                   <EmailNotifications :site="site" />
                </div> -->
<!-- 6 -->
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

                       <!-- <InputError message="message" class="mt-2" /> -->
                       
                    </div> 


                   

                    <PrimaryButton>
                        <PlusIcon />
                        Adicionar
                    </PrimaryButton>
                </form>
    </div>
</template>


<script setup>
import TextInput from '../Components/TextInput.vue';
import InputLabel from '../Components/InputLabel.vue';
import InputError from '../Components/InputError.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import { PlusIcon } from '@heroicons/vue/24/solid'
/* 3 */
const props = defineProps({
    site: Object,
});
/* 1 */
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

</script>



<!-- 7 ja esta retornando controcoller-->
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Arr;

class SiteNotificationsEmailStoreController extends Controller
{
    public function __invoke(Request $request, Site $site)
    {
        $emails = $site->notification_emails ?? [];
        array_unshift($emails, $request->input('email'));

        $site->update([
            'notification_emails' => $emails,
        ]);


         return back();
    }
}



<!-- 8 vamos criar request -->
php artisan make:request SiteNotificationsEmailStoreRequest
  public function rules(): array
    {
       $site = $this->route('site'); // pega o Site da rota
        $existingEmails = $site ? $site->notification_emails ?? [] : [];

        return [
            'email' => [
                'required',
                'email',
                'not_in:' . implode(',', $existingEmails),
            ],
        ];
    }




























