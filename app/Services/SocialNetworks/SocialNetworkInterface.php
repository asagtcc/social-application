<?php

namespace App\Services\SocialNetworks;

interface SocialNetworkInterface
{
    public function getAuthUrl(): string;
    public function getAccessToken(string $code): string;
    public function getUserProfile(string $accessToken): array;
    public function publishPost(string $accessToken, array $data): array;
}
