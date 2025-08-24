<?php

namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getAllByAccount($account,$status,$UserId);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
