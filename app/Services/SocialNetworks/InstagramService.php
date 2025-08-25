<?php

namespace App\Services\SocialNetworks;

use Illuminate\Support\Facades\Http;

class InstagramService implements SocialNetworkInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId     = config('services.instagram.client_id');
        $this->clientSecret = config('services.instagram.client_secret');
        $this->redirectUri  = config('services.instagram.redirect');
    }

    public function getAuthUrl(): string
    {
    $scopes = "instagram_basic,pages_show_list,instagram_content_publish";
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
        $shortResponse = Http::asForm()->post('https://graph.facebook.com/v17.0/oauth/access_token', [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'code'          => $code,
        ]);

        $shortLivedToken = $shortResponse->json()['access_token'] ?? null;

        if (!$shortLivedToken) {
            throw new \Exception('Failed to get short-lived access token: ' . json_encode($shortResponse->json()));
        }

        $longResponse = Http::asForm()->post('https://graph.facebook.com/v17.0/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->clientId,
            'client_secret'     => $this->clientSecret,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        $longLivedToken = $longResponse->json()['access_token'] ?? null;

        if (!$longLivedToken) {
            throw new \Exception('Failed to get long-lived access token: ' . json_encode($longResponse->json()));
        }

        return $longLivedToken;
    }

    public function getUserProfile(string $accessToken): array
    {
        $response = Http::asForm()->post("https://graph.facebook.com/me", [
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
