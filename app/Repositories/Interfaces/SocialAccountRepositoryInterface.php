<?php

namespace App\Repositories\Interfaces;

interface SocialAccountRepositoryInterface
{
    public function getAll();
    public function getBySlug($slug);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
