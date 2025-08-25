<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAllByType($type);
    public function getById($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
