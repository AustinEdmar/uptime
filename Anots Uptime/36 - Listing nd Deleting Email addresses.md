1 - no EmailNotifications.vue 
</form>


                <div class="mt-6">
                    <ul class="space-y-2">
                        <li class="flex item-center justify-between">
                         <span class="text-sm">Email</span>

                         <button>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                         </button>
                        </li>
                    </ul>
                </div> 

2 - vamos em siteResource enviar os email



  public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'domain' => $this->domain,
            'notification_emails' => $this->notification_emails,<!-- aqui -->

        ];
    }


<!-- 2 - testei  Dashboard.vue e funcionou -->
 </div>
                     {{ site.data.notification_emails }}
                <div class="grid grid-cols-3 gap-12 mt-4">
                   <EmailNotifications :site="site" />
                </div>
<!-- 3 agora passo por props -->
 <div class="grid grid-cols-3 gap-12 mt-4">
                   <EmailNotifications :site="site" :notification_emails="site.data.notification_emails" />
                </div>


<!-- 4 - EmailNotifications.vue -->
const props = defineProps({
    site: Object,
    notification_emails: Array,
});


<ul class="space-y-2" v-if="notification_emails && notification_emails.length > 0">
                        <li v-for="email in notification_emails" :key="email" class="flex item-center justify-between">
                         <span class="text-sm">{{ email }}</span>

<!-- 5 vamos fazer a logica de deletar emails -->
php artisan make:controller SiteNotificationsEmailDestroyController

Route::delete('/sites/{site}/notifications/emails', SiteNotificationsEmailDestroyController::class)->middleware(['auth', 'verified']);


class SiteNotificationsEmailDestroyController extends Controller
{
    
    public function __invoke()
    {
        dd("delete")
    }
}


<!-- vamos no vue EmailNotification.vue implemtar o botao -->
import { router } from '@inertiajs/vue3'

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

<!-- no controler para eleminar do email -->

<?php

namespace App\Http\Controllers;


use App\Models\Site;
use Illuminate\Http\Request;

class SiteNotificationsEmailDestroyController extends Controller
{
    
    public function __invoke(Request $request, Site $site)
    {

       

        $site->update([
            'notification_emails' => array_diff(
                $site->notification_emails, [$request->email]
            )
            ]);

            return back();
    }
}





