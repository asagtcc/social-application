<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\Organization\AddUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class OrganizationController extends Controller
{
 private OrganizationRepositoryInterface $OrganizationRepository;
 private UserRepositoryInterface $UserRepository;

    
    public function __construct(
        OrganizationRepositoryInterface $OrganizationRepository,
        UserRepositoryInterface $UserRepository
    )
    {
        $this->OrganizationRepository = $OrganizationRepository;
        $this->UserRepository = $UserRepository;
    }

    public function index()
    {
        $Organization = $this->OrganizationRepository->getAll();
        return OrganizationResource::collection($Organization);
    }
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

    if ($request->hasFile('logo')) {
        $userPath = $request->file('logo')->store('Organization', 'public');
        $data['logo'] = $userPath;
    }

        $Organization = $this->OrganizationRepository->create($attributes);
        return new OrganizationResource($Organization);
    }
    public function show($slug)
    {
        $Organization = $this->OrganizationRepository->getBySlug($slug);
        if (!$Organization) {
            return response()->json(['message' => 'Organization not found'], 409);
        }
        return new OrganizationResource($Organization);
    }

    public function addUser(AddUserRequest $request, $slug)
    {
        $Organization = $this->OrganizationRepository->getBySlug($slug);
        if (!$Organization) {
            return response()->json(['message' => 'Organization not found'], 409);
        }
        $data = $request->validated();


        if (!empty($data['user_id'])) {
            if ($Organization->users()->where('user_id', $data['user_id'])->exists()) {
                return response()->json([
                    'message' => 'User already exists in this Organization'
                ], 409);
            }

            $Organization->users()->attach($data['user_id']);
            $user = $Organization->users()->find($data['user_id']);
        } else {
            $user = $this->UserRepository->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'status'    => 1,
                'is_active'    => 1,
            ]);
            if(!$user){
                return response()->json(['message' => 'Failed to create user'], 500);
            }
             Mail::to($user->email)->send(new WelcomeMail($user));
            $Organization->users()->attach($user->id);
        }

        return response()->json([
            'message' => !empty($data['user_id']) 
                ? 'User added to Organization successfully' 
                : 'You created a new user and added to Organization successfully',
              'user' => new UserResource($user),
        ], 201);
    }

    public function removeUser($slug, $userId)
    {
        $Organization = $this->OrganizationRepository->getBySlug($slug);
        if (!$Organization) {
            return response()->json(['message' => 'Organization not found'], 409);
        }

        $exists = $Organization->users()->where('user_id', $userId)->exists();

        if (! $exists) {
            return response()->json([
                'message' => 'User does not belong to this Organization'
            ], 409);
        }

        $Organization->users()->detach($userId);

        return response()->json(['message' => 'User removed from Organization successfully'], 200);
    }
}
