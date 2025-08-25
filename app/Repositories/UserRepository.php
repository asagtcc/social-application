<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User; // Assumption: You have a Model with the same name

class UserRepository implements UserRepositoryInterface
{
    public function getAllByType($type)
    {
        return User::query()
         ->where('type',$type)->paginate(10);

        return User::getAllByType($type);
    }

    public function getById($id)
    {
     return User::with('organizations')->find($id);
    }

    public function create(array $attributes)
    {
        return User::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = User::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = User::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
