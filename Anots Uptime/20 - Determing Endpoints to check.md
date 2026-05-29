1 - vamos fazer a parte de determinar quais endpoints devem ser checados
temos um comando central no console usara na proxima data ou horario veriicaçaoe encontra todos os endpoints que devem ser checados

<!-- docker-compose run --rm artisan make:command PerformChecks -->

    protected $signature = 'checks:perform'; //aqui vai ser executado o comando de perform checks

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform checks on endpoints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return Command::SUCCESS;
    }


<!-- 2  rodo docker-compose run --rm artisan checks:perform --help -->

Endpoint::where('next_check', '<=', now())

Busca na tabela endpoints

Todos os registros onde:

next_check <= agora


Ou seja 👉 endpoints que já estão prontos para serem verificados novamente

<!--  public function handle()
    {
       // “Pegue todos os endpoints cujo next_check já venceu e execute uma função para cada um deles”.
        $endpoints = Endpoint::where('next_check', '<=', now())->each(function () {

        });


        dump($endpoints);
        return Command::SUCCESS;
    } -->