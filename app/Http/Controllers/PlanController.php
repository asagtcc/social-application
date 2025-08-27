<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PlanResource;
use App\Http\Requests\Plan\StoreRequest;
use App\Http\Requests\Plan\UpdateRequest;
use App\Repositories\Interfaces\PlanRepositoryInterface;

class PlanController extends Controller
{
private PlanRepositoryInterface $PlanRepository;
    
    public function __construct(PlanRepositoryInterface $PlanRepository,)
    {$this->PlanRepository = $PlanRepository;}

 
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
}
