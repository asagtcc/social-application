<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post; // Assumption: You have a Model with the same name

class PostRepository implements PostRepositoryInterface
{
    public function getAllByAccount($account,$status,$UserId)
    {
        return Post::query()
            ->where('social_account_id', $account)
            ->where('user_id', $UserId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAll()
    {
        return Post::all();
    }

    public function getById($id)
    {
        return Post::find($id);
    }

    public function create(array $attributes)
    {
        return Post::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = Post::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = Post::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
