<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Resources\DonationResource;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DonationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $donations = Donation::where('user_id', auth()->id())
            ->with(['campaign', 'user'])
            ->latest()
            ->paginate(15);

        return DonationResource::collection($donations);
    }

    public function store(StoreDonationRequest $request, Campaign $campaign): JsonResponse
    {
        if (!$campaign->is_active) {
            return response()->json([
                'message' => 'This campaign is not currently accepting donations.',
            ], 422);
        }

        $donation = Donation::create([
            'user_id' => auth()->id(),
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'message' => $request->message,
            'is_anonymous' => $request->is_anonymous,
        ]);

        $campaign->increment('current_amount', $request->amount);

        return response()->json([
            'message' => 'Donation successful',
            'donation' => new DonationResource($donation->load(['campaign', 'user'])),
        ], 201);
    }

    public function show(Donation $donation): DonationResource
    {
        $this->authorize('view', $donation);

        return new DonationResource($donation->load(['campaign', 'user']));
    }

    public function destroy(Donation $donation): Response
    {
        $this->authorize('delete', $donation);

        $donation->delete();

        return response()->noContent();
    }
} 