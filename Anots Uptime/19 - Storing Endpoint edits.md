1 - docker-compose run --rm artisan make:controller EndpointUpdateController  

2 - Route::patch('/endpoints/{endpoint}', EndpointUpdateController::class)->middleware(['auth', 'verified']);



<!-- 3 - Update the endpoint -->

class EndpointUpdateController extends Controller
{
    public function __invoke(Request $request, $endpoint)
    {
        dd($endpoint);
    }
}



<!-- 4 - Update endpoint.vue to use patch method -->

 //debounce para evitar muitas requisições
    const editEndpoint = debounce(() => {
        endpointForm.patch(`/endpoints/${props.endpoint.id}`, {
            preserveScroll: true,
        });
    }, 500);

   //dirty Qualquer mudança no formulário ativa o watcher
   watch(() => endpointForm.isDirty, () => {
    
      editEndpoint();
    });


<!-- 5 agora voltamos no EndpointUpdateController -->

class EndpointUpdateController extends Controller
{
    public function __invoke(EndpointUpdateRequest $request, Endpoint $endpoint)
    {
        //dd($endpoint);
        $endpoint->update($request->only('location', 'frequency'));
        
        return back();
    }
}

<!-- 6 na model site deixei latest -->
 public function endpoints()
    {
        return $this->hasMany(Endpoint::class)->latest();
    }


<!-- 7 vamos criar request  -->
docker-compose run --rm artisan make:request EndpointUpdateRequest      

public function rules(): array
    {
        return [
            'location' => 'required',
            'frequency' => ['required', new Enum(EndpointFrequency::class)]
        ];
    }


<!-- adicionei policy -->

use App\Models\Endpoint;
use App\Http\Requests\EndpointUpdateRequest;
use Illuminate\Support\Facades\Gate;

class EndpointUpdateController extends Controller
{
    public function __invoke(EndpointUpdateRequest $request, Endpoint $endpoint)
    {
        //dd($endpoint);
         if (! Gate::allows('update-endpoint', $endpoint)) {
                abort(403);
            }
        $endpoint->update($request->only('location', 'frequency'));
        
        return back();
    }
}


