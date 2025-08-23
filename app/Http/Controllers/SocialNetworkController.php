<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SocialNetworkResource;
use App\Http\Requests\SocialNetwork\SocialNetworkRequest;
use App\Repositories\Interfaces\SocialNetworkRepositoryInterface;

class SocialNetworkController extends Controller
{
 private SocialNetworkRepositoryInterface $SocialNetworkRepository;
    
    public function __construct(SocialNetworkRepositoryInterface $SocialNetworkRepository)
    {
        $this->SocialNetworkRepository = $SocialNetworkRepository;
    }
    public function index()
    {
        $socialNetworks = $this->SocialNetworkRepository->getAll();
        return SocialNetworkResource::collection($socialNetworks);
    }
    public function store(SocialNetworkRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('icon')) {
            $iconPath  = $request->file('icon')->store('social', 'public');
            $data['icon'] = $iconPath ;
        }
        $socialNetwork = $this->SocialNetworkRepository->create($data);
        return new SocialNetworkResource($socialNetwork);
    }
    public function show($slug)
    {
        $socialNetwork = $this->SocialNetworkRepository->getBySlug($slug);
        if (!$socialNetwork) {
            return response()->json(['message' => 'Social Network not found'], 409);
        }
        return new SocialNetworkResource($socialNetwork);
    }
    
    public function update(SocialNetworkRequest $request, $slug)
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            $iconPath  = $request->file('icon')->store('social', 'public');
            $data['icon'] = $iconPath ;
        }
        $socialNetwork = $this->SocialNetworkRepository->update($slug, $data);
        if (!$socialNetwork) {
            return response()->json(['message' => 'Social Network not found'], 409);
        }
        return new SocialNetworkResource($socialNetwork);
    }

    public function destroy($slug)
    {
        $deleted = $this->SocialNetworkRepository->delete($slug);
        if (!$deleted) {
            return response()->json(['message' => 'Social Network not found'], 409);
        }
        return response()->json(['message' => 'Social Network deleted successfully'], 200);
    }
   
}
