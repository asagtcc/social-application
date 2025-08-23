<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OrganisationRepositoryInterface;
use App\Models\Organisation; // Assumption: You have a Model with the same name

class OrganisationRepository implements OrganisationRepositoryInterface
{
    public function getAll()
    {
         return Organisation::query()
         ->with('users')->paginate(10);
    }

    public function getBySlug($slug)
    {
        return Organisation::where('slug', $slug)
            ->with('users') 
            ->first();
    }

    public function create(array $attributes)
    {
        return Organisation::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = Organisation::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = Organisation::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
