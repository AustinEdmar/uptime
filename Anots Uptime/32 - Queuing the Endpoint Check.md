adiciono em jobs 
class PerformEndpointCheck implements ShouldQueue
interface ShouldBeUnique e o método uniqueId() servem para garantir que não existam jobs duplicados na fila ao mesmo tempo

<!-- <?php

namespace App\Jobs;

use App\Models\Endpoint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerformEndpointCheck implements ShouldQueue, ShouldBeUnique aqui########
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Endpoint $endpoint )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{

            $response = Http::get($this->endpoint->url());

            

        } catch(\Exception $e){
           
        }



        //vamos criar um check
        $this->endpoint->checks()->create([
            'response_code' => $response->status(),
            'response_body' => !$response->successful() ? $response->body() : null,
        ]);

        //este metodo fara a requisiçao no endpoint expecifico, actualizara o endpoint com novo next_check
        // é importante porque se nao actualizarmos ele vai continuar verificacndo
        // toda vez que rodarmos o cronograma, roda a cada segundo,
        //nao queremos fazer requisiçao a cada segunda entao vamos COMMAND PERFORM CHECKS, dispachar o job para que funcione


        // vamos modificar e adicionar certas quantidades de segundos
        $this->endpoint->update([
            'next_check' => now()->addSeconds($this->endpoint->frequency)
        ]);
    }
    
    public function uniqueId(): string Aqui########
    {
        return $this->endpoint->id;
    }
}
 -->




 2 - de momento deixei assim
 
 <?php

namespace App\Jobs;

use App\Models\Endpoint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerformEndpointCheck implements ShouldQueue/* , ShouldBeUnique */
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Endpoint $endpoint )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{

            $response = Http::get($this->endpoint->url());
          
            

        } catch(\Exception $e){
           
        }



        //vamos criar um check
        $this->endpoint->checks()->create([
            'response_code' => $response->status(),
            'response_body' => !$response->successful() ? $response->body() : null,
        ]);

        //este metodo fara a requisiçao no endpoint expecifico, actualizara o endpoint com novo next_check
        // é importante porque se nao actualizarmos ele vai continuar verificacndo
        // toda vez que rodarmos o cronograma, roda a cada segundo,
        //nao queremos fazer requisiçao a cada segunda entao vamos COMMAND PERFORM CHECKS, dispachar o job para que funcione


        // vamos modificar e adicionar certas quantidades de segundos
        $this->endpoint->update([
            'next_check' => now()->addSeconds($this->endpoint->frequency)
        ]);
    }

  
}
