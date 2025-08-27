<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PlanRepositoryInterface;
use App\Models\Plan; // Assumption: You have a Model with the same name

class PlanRepository implements PlanRepositoryInterface
{
    public function getAll()
    {
        return Plan::all();
    }
    public function getActive()
    {
        return Plan::all();
    }

    public function getBySlug($slug)
    {
        return Plan::where('slug', $slug)
            ->with('organizations') 
            ->first();
    }


    public function create(array $attributes)
    {
        return Plan::create($attributes);
    }

    public function update($slug, array $attributes)
    {
        $record = Plan::query()
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
        $record = Plan::query()
            ->where('slug', $slug)
            ->first();
        if ($record) {
            return $record->delete();
        }
    }
}
