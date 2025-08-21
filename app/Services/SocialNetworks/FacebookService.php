<?php

namespace App\Services\SocialNetworks;

use Illuminate\Support\Facades\Http;

class FacebookService implements SocialNetworkInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId     = config('services.facebook.client_id');
        $this->clientSecret = config('services.facebook.client_secret');
        $this->redirectUri  = config('services.facebook.redirect');
    }

    public function getAuthUrl(): string
    {
        $scopes = "pages_manage_posts,pages_show_list,pages_read_engagement,public_profile";

        return "https://www.facebook.com/v17.0/dialog/oauth?"
            . http_build_query([
                'client_id'     => $this->clientId,
                'redirect_uri'  => $this->redirectUri,
                'scope'         => $scopes,
                'response_type' => 'code',
            ]);
    }

    public function getAccessToken(string $code): string
    {
        $response = Http::get('https://graph.facebook.com/v17.0/oauth/access_token', [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
        ]);

        return $response->json()['access_token'] ?? '';
    }

    public function getUserProfile(string $accessToken): array
    {
        $response = Http::get("https://graph.facebook.com/me", [
            'fields'        => 'id,name,picture',
            'access_token'  => $accessToken,
        ]);

        return $response->json();
    }

    public function publishPost(string $accessToken, array $data): array
    {
        $response = Http::post("https://graph.facebook.com/me/feed", [
            'message'       => $data['message'] ?? '',
            'access_token'  => $accessToken,
        ]);

        return $response->json();
    }
}
