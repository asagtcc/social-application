<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\Organization\StoreRequest;
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
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $userPath = $request->file('logo')->store('Organization', 'public');
                $data['logo'] = $userPath;
            }

            $organization = $this->OrganizationRepository->create($data);
            return new OrganizationResource($organization);

         } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create organization',
                'error' => $e->getMessage()
            ], 409);
        }
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
                'verification_token' => Str::random(60),
                'status'    => 0,
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
    public function destroy($slug)
    {
        $Organization = $this->OrganizationRepository->getBySlug($slug);
        if (!$Organization) {
            return response()->json(['message' => 'Organization not found'], 409);
        }
        $this->OrganizationRepository->delete($Organization->id);
        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }
    public function update(StoreRequest $request, $slug)
    {
        try {
            $Organization = $this->OrganizationRepository->getBySlug($slug);
            if (!$Organization) {
                return response()->json(['message' => 'Organization not found'], 409);
            }

            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $userPath = $request->file('logo')->store('Organization', 'public');
                $data['logo'] = $userPath;
            }
            $data = array_filter($data, fn($value) => !empty($value));
            $this->OrganizationRepository->update($Organization->id, $data);

            $updatedOrganization = $this->OrganizationRepository->getBySlug($slug);
            return new OrganizationResource($updatedOrganization);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update organization',
                'error' => $e->getMessage()
            ], 409);
        }
    }
}
