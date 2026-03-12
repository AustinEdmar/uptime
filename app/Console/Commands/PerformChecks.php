<?php

namespace App\Console\Commands;


use App\Jobs\PerformEndpointCheck;
use App\Models\Endpoint;
use Illuminate\Console\Command;

class PerformChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
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
        // “Pegue todos os endpoints cujo next_check já venceu e execute uma função para cada um deles”.
        $endpoints = Endpoint::where('next_check', '<=', now())->each(function ($endpoint) {
            PerformEndpointCheck::dispatch($endpoint);

        });

       // dump($endpoints);
        return Command::SUCCESS;
    }
}
