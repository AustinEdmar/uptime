1 . tamanho do endpoints.data.length
   </PrimaryButton>
                </form>

                 <div class="mt-8 flex flex-col">
                    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                        Currently monitoring ({{ endpoints.data.length }})
                    </h2>


<!-- 2 vamos a logica de delete  -->
docker-compose run --rm artisan make:controller EndpointDestroyController

<!-- rota -->
Route::delete('/endpoints/{endpoint}', EndpointDestroyController::class)->middleware(['auth', 'verified']);


<!-- logica -->
public function __invoke(Request $request, Endpoint $endpoint)
    {
        dd($endpoint);    
    }

<!-- agora no vue testar Endpoint.vue -->

<td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 w-16">
            <button @click="deleteEndpoint" class="text-red-600 hover:text-red-900">
                Delete
            </button>
        </td>
    </tr>
</template>


<script setup>

    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        endpoint: Object,
    });


    const deleteForm = useForm({
        endpoint_id: props.endpoint.id,
    });


     const deleteEndpoint = () => {
        if (confirm('Tem certeza que deseja deletar este endpoint?')) {
            deleteForm.delete(`/endpoints/${props.endpoint.id}`);
        }
    }

</script>


<!-- vamos criar a policy para deletar so quem pode deletar -->
docker-compose run --rm artisan make:policy EndpointPolicy --model=Endpoint

<?php

namespace App\Policies;

use App\Models\Endpoint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EndpointPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Endpoint $endpoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Endpoint $endpoint): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Endpoint $endpoint) ############ aqui
    {
        return $user->id === $endpoint->site->user_id
            ? Response::allow()
            : Response::deny('Você não pode deletar este endpoint.');
    }


<!-- 3 vamos no appServiceProvider -->
<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Site;
use App\Observers\EndpointObserver;
use App\Observers\SiteObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\SitePolicy;
use App\Policies\EndpointPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
        Endpoint::observe(EndpointObserver::class);


        Gate::define('delete-endpoint', [EndpointPolicy::class, 'delete']); ############ aqui
       
        
    }
}


<!-- 4 vamos no endpointDestroyController -->
class EndpointDestroyController extends Controller
{
    
    public function __invoke(Request $request, Endpoint $endpoint)
    {
          if (! Gate::allows('delete-endpoint', $endpoint)) {
            abort(403);
        }
        $endpoint->delete();

        return back();
    }
}


