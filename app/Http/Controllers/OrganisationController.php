<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OrganisationResource;
use App\Repositories\Interfaces\OrganisationRepositoryInterface;

class OrganisationController extends Controller
{
 private OrganisationRepositoryInterface $OrganisationRepository;
    
    public function __construct(OrganisationRepositoryInterface $OrganisationRepository)
    {
        $this->OrganisationRepository = $OrganisationRepository;
    }

    public function index()
    {
        $organisations = $this->OrganisationRepository->getAll();
        return OrganisationResource::collection($organisations);
    }
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

    if ($request->hasFile('logo')) {
        $userPath = $request->file('logo')->store('organisations', 'public');
        $data['logo'] = $userPath;
    }

        $organisation = $this->OrganisationRepository->create($attributes);
        return new OrganisationResource($organisation);
    }
    public function show($slug)
    {
        $organisation = $this->OrganisationRepository->getBySlug($slug);
        if (!$organisation) {
            return response()->json(['message' => 'Organisation not found'], 409);
        }
        return new OrganisationResource($organisation);
    }

    public function addUser(Request $request, $slug)
    {
        $organisation = $this->OrganisationRepository->getBySlug($slug);
        if (!$organisation) {
            return response()->json(['message' => 'Organisation not found'], 409);
        }

        $attributes = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
        ]);

        if ($organisation->users()->where('user_id', $attributes['user_id'])->exists()) {
            return response()->json([
                'message' => 'User already exists in this organisation'
            ], 409); 
        }

        $organisation->users()->attach($attributes['user_id']);

        return response()->json([
            'message' => 'User added to organisation successfully'
        ], 201);

    }

    public function removeUser($slug, $userId)
    {
        $organisation = $this->OrganisationRepository->getBySlug($slug);
        if (!$organisation) {
            return response()->json(['message' => 'Organisation not found'], 409);
        }

        $exists = $organisation->users()->where('user_id', $userId)->exists();

        if (! $exists) {
            return response()->json([
                'message' => 'User does not belong to this organisation'
            ], 409);
        }

        $organisation->users()->detach($userId);

        return response()->json(['message' => 'User removed from organisation successfully'], 200);
    }
}
