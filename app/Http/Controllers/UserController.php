<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
private UserRepositoryInterface $UserRepository;
    
    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function profile(Request $request)
    {
        $user = $this->UserRepository->getById($request->user()->id);
        return UserResource::make($user);

    }

    public function update(UserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $userPath = $request->file('photo')->store('user', 'public');
            $data['photo'] = $userPath;
        }
        $data = array_filter($data, fn($value) => !empty($value));

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->UserRepository->update($request->user()->id, $data);
        return response()->json([
            'message' => 'تم تحديث الحساب بنجاح',
            'data'    => UserResource::make($user)
        ]);
     
    }

    public function delete(Request $request)
    {
        $this->UserRepository->delete($request->user()->id);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
