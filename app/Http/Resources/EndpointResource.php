<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\EndpointFrequency;
use App\Http\Resources\CheckResource;
use App\Http\Resources\SiteResource;

class EndpointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
        public function toArray(Request $request): array
        {
            return [
                'id' => $this->id,
                'location' => $this->location,
                'frequency_label' => EndpointFrequency::from($this->frequency)->label(),

                'frequency_value' => EndpointFrequency::from($this->frequency)->value,
                
                'lastest_check' => CheckResource::make($this->check), // $this->check vem no relacionamento endpoint
                
                'url' => $this->url(),
                'site' => SiteResource::make($this->site),
                'checks' => CheckResource::collection($this->checks),
                'uptime_percentage' => $this->uptimePercentage(),
                
            ];
        }
    }

