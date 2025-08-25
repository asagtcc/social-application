<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\AdminResource;
use App\Http\Requests\Admin\AdminRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AdminController extends Controller
{
private UserRepositoryInterface $UserRepository;
    
    public function __construct(UserRepositoryInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function index(Request $request)
    {
        $users = $this->UserRepository->getAllByType("admin");
         return AdminResource::collection($users);
    }

    public function store(AdminRequest $request)
    {
        $data = $request->validated();
  
        $data['password'] = bcrypt($data['password']);
         $photo = Arr::pull($data, 'photo');
         
        $user = $this->UserRepository->create($data);

        try {
            if ($photo) {
                $user->addMedia($photo)->toMediaCollection('user');
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل رفع الصورة',
                'error'   => $e->getMessage(),
            ], 500);
        }

        return response()->json(
            [
                'message' => 'Admin created successfully', 
                'data' => AdminResource::make($user)
            ], 201);
    }

    public function show($id)
    {
        $user = $this->UserRepository->getById($id);
        
        return response()->json(
            [
                'message' => 'Admin information', 
                'data' => AdminResource::make($user)
            ], 201);

    }
    public function update(AdminRequest $request, $id)
    {
        $data = $request->validated();
        
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $photo = Arr::pull($data, 'photo');
        $user = $this->UserRepository->update($id, $data);
        if ($photo) {
            $user->clearMediaCollection('user');
            $user->addMedia($photo)
                ->toMediaCollection('user'); 
        }
        return response()->json(['message' => 'Admin updated successfully', 'data' => $user]);
    }

    public function destroy(Request $request, $id)
    {
        $this->UserRepository->delete($id);
        return response()->json(['message' => 'Admin deleted successfully'], 200);
    }


}
