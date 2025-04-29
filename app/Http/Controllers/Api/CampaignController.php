<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CampaignController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $campaigns = QueryBuilder::for(Campaign::class)
            ->allowedFilters(['title', 'category', 'status'])
            ->allowedSorts(['created_at', 'target_amount', 'current_amount'])
            ->defaultSort('-created_at')
            ->paginate($request->per_page ?? 15);

        return CampaignResource::collection($campaigns);
    }

    public function store(StoreCampaignRequest $request): CampaignResource
    {
        $campaign = Campaign::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return new CampaignResource($campaign);
    }

    public function show(Campaign $campaign): CampaignResource
    {
        return new CampaignResource($campaign->load(['user', 'donations']));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        $this->authorize('update', $campaign);

        $campaign->update($request->validated());

        return new CampaignResource($campaign);
    }

    public function destroy(Campaign $campaign): Response
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return response()->noContent();
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = $request->get('query');

        $campaigns = Campaign::query()
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate($request->per_page ?? 15);

        return CampaignResource::collection($campaigns);
    }
} 