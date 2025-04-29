<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\DonationResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function campaigns(User $user): AnonymousResourceCollection
    {
        $campaigns = $user->campaigns()
            ->with(['user', 'donations'])
            ->latest()
            ->paginate(15);

        return CampaignResource::collection($campaigns);
    }

    public function donations(User $user): AnonymousResourceCollection
    {
        $donations = $user->donations()
            ->with(['campaign', 'user'])
            ->latest()
            ->paginate(15);

        return DonationResource::collection($donations);
    }
} 