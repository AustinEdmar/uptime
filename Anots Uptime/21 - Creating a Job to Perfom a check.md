1 - docker-compose run --rm artisan make:job PerfomEndpointCheck
vai realizar a verificaçao

<!-- removi o implements ShouldQueue, para ver a mudar sem ainda colocar na fila -->


public function handle(): void
    {
        //este metodo fara a requisiçao no endpoint expecifico, actualizara o endpoint com novo next_check
        // é importante porque se nao actualizarmos ele vai continuar verificacndo
        // toda vez que rodarmos o cronograma, roda a cada segundo,
        //nao queremos fazer requisiçao a cada segunda entao vamos COMMAND PERFORM CHECKS, dispachar o job para que funcione
    }

    <!-- 2 no Command -->

     public function handle()
    {
        // “Pegue todos os endpoints cujo next_check já venceu e execute uma função para cada um deles”.
        $endpoints = Endpoint::where('next_check', '<=', now())->each(function ($endpoint) {
            PerformEndpointCheck::dispatch($endpoint);

        });

        dump($endpoints);
        return Command::SUCCESS;
    }


<!-- 3 volto no job -->
no construc coloco

  * Create a new job instance.
     */
    public function __construct(public Endpoint $endpoint )
    {
        //
    }


    public function handle(): void
    {
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



<!-- 4 - vamos executar o comando  -->
docker-compose run --rm artisan checks:perform











