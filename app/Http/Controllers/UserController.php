<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\OrganizationResource;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\OrganizationRepository;

class UserController extends Controller
{
private UserRepositoryInterface $UserRepository;
    
    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function profile(Request $request)
    {
        $user = $this->UserRepository->getById(auth()->id());
        return UserResource::make($user);

    }

    public function organizations(Request $request)
    {
        $user = $this->UserRepository->getById(auth()->id());
        $organizations = $user->organizations;

        return response()->json([
            'message' => 'المنظمات التي ينتمي إليها المستخدم',
            'data'    => OrganizationResource::collection($organizations)
        ]);
    }
    public function update(UserRequest $request)
    {
        $data = $request->validated();

        $photo = Arr::pull($data, 'photo');
     
        $data = array_filter($data, fn($value) => !empty($value));

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->UserRepository->update(auth()->id(), $data);
        if ($photo) {
            $user->clearMediaCollection('user');
            $user->addMedia($photo)
                ->toMediaCollection('user'); 
        }
        return response()->json([
            'message' => 'تم تحديث الحساب بنجاح',
            'data'    => UserResource::make($user)
        ]);
     
    }

    public function delete(Request $request)
    {
        $this->UserRepository->delete(auth()->id());
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
