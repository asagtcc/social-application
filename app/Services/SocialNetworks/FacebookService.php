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
        $scopes = "public_profile,email,pages_show_list,pages_manage_posts,pages_read_engagement";

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

        $shortToken = $shortResponse->json()['access_token'] ?? null;

        if (!$shortToken) {
            throw new \Exception('Failed to get short-lived token: ' . json_encode($shortResponse->json()));
        }

        $longResponse = Http::asForm()->post('https://graph.facebook.com/v17.0/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->clientId,
            'client_secret'     => $this->clientSecret,
            'fb_exchange_token' => $shortToken,
        ]);

        $longToken = $longResponse->json()['access_token'] ?? null;

        if (!$longToken) {
            throw new \Exception('Failed to get long-lived token: ' . json_encode($longResponse->json()));
        }

        return $longToken;
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
        $targetId = $data['target_id'] ?? 'me';
        $endpoint = "https://graph.facebook.com/{$targetId}/feed";

        $payload = [
            'message'      => $data['message'] ?? '',
            'link'         => $data['link'] ?? null, 
            'access_token' => $accessToken,
        ];

        $payload = array_filter($payload);

        $response = Http::asForm()->post($endpoint, $payload);

        if ($response->failed()) {
            throw new \Exception(
                'Failed to publish post: ' . json_encode($response->json())
            );
        }

        return $response->json();
    }

    // public function publishPost(string $accessToken, array $data): array
    // {
    //     $response = Http::post("https://graph.facebook.com/me/feed", [
    //         'message'       => $data['message'] ?? '',
    //         'access_token'  => $accessToken,
    //     ]);

    //     return $response->json();
    // }
}
