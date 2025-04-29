<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'message' => $this->message,
            'is_anonymous' => $this->is_anonymous,
            'donor_name' => $this->donor_name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
        ];
    }
} 