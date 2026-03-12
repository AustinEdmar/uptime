<?php

namespace App\Jobs;

use App\Models\Endpoint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerformEndpointCheck implements ShouldQueue, ShouldBeUnique
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
    try {
        $response = Http::withoutVerifying()
    ->timeout(20)
    ->withHeaders([
        'User-Agent' => 'Mozilla/5.0',
    ])
    ->get($this->endpoint->url());

        \Log::info('Request OK', [
            'url' => $this->endpoint->url(),
            'status' => $response->status(),
        ]);

    } catch (\Throwable $e) {

        \Log::error('Request FAILED', [
            'url' => $this->endpoint->url(),
            'error' => $e->getMessage(),
        ]);

        $this->endpoint->checks()->create([
            'response_code' => 0,
            'response_body' => $e->getMessage(),
        ]);

        return;
    }

    $this->endpoint->checks()->create([
        'response_code' => $response->status(),
        'response_body' => !$response->successful() ? $response->body() : null,
    ]);

    $this->endpoint->update([
        'next_check' => now()->addSeconds($this->endpoint->frequency),
    ]);
}


    public function uniqueId(): string
    {
        return $this->endpoint->id;
    }


  
}
