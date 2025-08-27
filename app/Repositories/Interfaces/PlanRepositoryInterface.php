<?php

namespace App\Repositories\Interfaces;

interface PlanRepositoryInterface
{
    public function getAll();
    public function getActive();
    public function getBySlug($slug);
    public function create(array $attributes);
    public function update($slug, array $attributes);
    public function delete($slug);
}
