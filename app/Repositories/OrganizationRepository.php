<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use App\Models\organization; // Assumption: You have a Model with the same name

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function getAll()
    {
         return organization::query()
         ->with('users')->paginate(10);
    }

    public function getBySlug($slug)
    {
        return organization::where('slug', $slug)
            ->with('users') 
            ->first();
    }

    public function create(array $attributes)
    {
        return organization::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = organization::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = organization::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
