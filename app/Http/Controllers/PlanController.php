<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PlanResource;
use App\Traits\ResolvesOrganization;
use App\Http\Requests\Plan\StoreRequest;
use App\Http\Requests\Plan\UpdateRequest;
use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class PlanController extends Controller
{
    use ResolvesOrganization;
    private PlanRepositoryInterface $PlanRepository;
    private OrganizationRepositoryInterface $OrganizationRepository;
    public function __construct(
        PlanRepositoryInterface $PlanRepository, OrganizationRepositoryInterface $OrganizationRepository,
    )
    {
        $this->PlanRepository = $PlanRepository;
        $this->OrganizationRepository = $OrganizationRepository;
    }

 
    public function index(Request $request)
    {
        $plans = $this->PlanRepository->getActive();
         return PlanResource::collection($plans);
    }
    public function show($slug)
    {
        $plan = $this->PlanRepository->getBySlug($slug);
        if ($plan) {
            return new PlanResource($plan);
        }
        return response()->json(['message' => 'Plan not found'], 404);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $plan = $this->PlanRepository->create($data);
        return new PlanResource($plan);
    }

    public function update(UpdateRequest $request, $slug)
    {
        $data = $request->validated();
        $data = array_filter($data, fn($value) => $value !== null);
        $plan = $this->PlanRepository->update($slug, $data);
        if ($plan) {
            return new PlanResource($plan);
        }
        return response()->json(['message' => 'Plan not found'], 404);
    }

    public function destroy($slug)
    {
        $deleted = $this->PlanRepository->delete($slug);
        if ($deleted) {
            return response()->json(['message' => 'Plan deleted successfully']);
        }
        return response()->json(['message' => 'Plan not found'], 404);
    }

    public function subscribe(Request $request,  $slug)
    {
        $organization = $this->resolveOrganization($request, $this->OrganizationRepository);
        
        $plan = $this->PlanRepository->getBySlug($slug);
           if (!$plan) {
            return response()->json([
                'message' => 'plan not found'
            ], 400);
        }
      
        // $organization = $this->OrganizationRepository->getBySlug($organizationSlug);
        //    if (!$organization) {
        //     return response()->json([
        //         'message' => 'Organization not found'
        //     ], 400);
        // }

        // if ($organization->plans()->where('plans.id', $plan->id)->exists()) {
        //     return response()->json([
        //         'message' => 'Organization already subscribed to this plan',
        //     ], 409);
        // }

        ////

        // // نربط المنظمة بالبلان عن طريق الجدول الوسيط subscriptions
        // $organization->plans()->attach($plan->id, [
        //     'status'     => 'active',
        //     'started_at' => now(),
        //     'expires_at' => now()->addMonth(), // مثلاً شهر واحد
        // ]);

        // return response()->json([
        //     'message' => 'Subscribed successfully',
        //     'plan'    => $plan->name,
        //     'org'     => $organization->name,
        // ]);
  
    }
   
}
