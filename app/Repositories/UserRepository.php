<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User; // Assumption: You have a Model with the same name

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function getById($id)
    {
        return User::find($id);
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
