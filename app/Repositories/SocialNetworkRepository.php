<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SocialNetworkRepositoryInterface;
use App\Models\SocialNetwork; // Assumption: You have a Model with the same name

class SocialNetworkRepository implements SocialNetworkRepositoryInterface
{
    public function getAll()
    {
        return SocialNetwork::all();
    }

    public function getBySlug($slug)
    {
        return SocialNetwork::where('slug', $slug)->first();
    }

    public function create(array $attributes)
    {
        return SocialNetwork::create($attributes);
    }

    public function update($slug, array $attributes)
    {
        $record = SocialNetwork::query()
            ->where('slug', $slug)
            ->first();
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($slug)
    {
        $record = SocialNetwork::query()
            ->where('slug', $slug)
            ->first();
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
