<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\OrganisationResource;
use App\Http\Requests\Organisation\AddUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\OrganisationRepositoryInterface;

class OrganisationController extends Controller
{
 private OrganisationRepositoryInterface $OrganisationRepository;
 private UserRepositoryInterface $UserRepository;

    
    public function __construct(
        OrganisationRepositoryInterface $OrganisationRepository,
        UserRepositoryInterface $UserRepository
    )
    {
        $this->OrganisationRepository = $OrganisationRepository;
        $this->UserRepository = $UserRepository;
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

    public function addUser(AddUserRequest $request, $slug)
    {
        $organisation = $this->OrganisationRepository->getBySlug($slug);
        if (!$organisation) {
            return response()->json(['message' => 'Organisation not found'], 409);
        }
        $data = $request->validated();


        if (!empty($data['user_id'])) {
            if ($organisation->users()->where('user_id', $data['user_id'])->exists()) {
                return response()->json([
                    'message' => 'User already exists in this organisation'
                ], 409);
            }

            $organisation->users()->attach($data['user_id']);
            $user = $organisation->users()->find($data['user_id']);
        } else {
            $user = $this->UserRepository->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'status'    => 1,
                'is_active'    => 1,
            ]);

            $organisation->users()->attach($user->id);
        }

        return response()->json([
            'message' => !empty($data['user_id']) 
                ? 'User added to organisation successfully' 
                : 'You created a new user and added to organisation successfully',
              'user' => new UserResource($user),
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
