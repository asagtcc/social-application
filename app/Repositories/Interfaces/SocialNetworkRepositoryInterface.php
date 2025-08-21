<?php

namespace App\Repositories\Interfaces;

interface SocialNetworkRepositoryInterface
{
    public function getAll();
    public function getBySlug($slug);
    public function create(array $attributes);
    public function update($slug, array $attributes);
    public function delete($slug);
}
