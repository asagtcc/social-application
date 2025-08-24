<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SocialAccountRepositoryInterface;
use App\Models\SocialAccount; // Assumption: You have a Model with the same name

class SocialAccountRepository implements SocialAccountRepositoryInterface
{
    public function getAll()
    {
        return SocialAccount::all();
    }

    public function getByAccountId($id)
    {
        return SocialAccount::query()
            ->whereHas('socialNetwork', function ($query) use ($id) {
                $query->where('account_id', $id);
            })
            ->first();
    }
    public function getBySlug($slug)
    {
        return SocialAccount::query()
            ->whereHas('socialNetwork', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->first();
    }

    public function create(array $attributes)
    {
        return SocialAccount::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = SocialAccount::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = SocialAccount::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
