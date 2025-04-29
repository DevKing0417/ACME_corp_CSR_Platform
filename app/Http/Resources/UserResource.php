<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'campaigns_count' => $this->whenLoaded('campaigns', fn () => $this->campaigns->count()),
            'donations_count' => $this->whenLoaded('donations', fn () => $this->donations->count()),
            'total_donated' => $this->whenLoaded('donations', fn () => $this->donations->sum('amount')),
        ];
    }
} 