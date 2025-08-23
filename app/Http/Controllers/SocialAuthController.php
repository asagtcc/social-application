<?php
namespace App\Http\Controllers;
use App\Services\SocialNetworks\FacebookService;
use Illuminate\Http\Request;


class SocialAuthController extends Controller
{
   protected $services;

    public function __construct(FacebookService $facebook)
    {
        $this->services = [
            'facebook' => $facebook,
        ];
    }

     protected function resolveService(string $provider)
    {
        if (!isset($this->services[$provider])) {
            abort(409, "Provider not supported");
        }
        return $this->services[$provider];
    }

    public function redirect(string $provider)
    {
        $service = $this->resolveService($provider);
        return response()->json([
            'url' => $service->getAuthUrl()
        ]);
    }

//  public function callback(Request $request, string $provider)
// {
//     $code = $request->get('code');

//     $response = Http::get('https://graph.facebook.com/v17.0/oauth/access_token', [
//         'client_id'     => config('services.facebook.client_id'),
//         'client_secret' => config('services.facebook.client_secret'),
//         'redirect_uri'  => config('services.facebook.redirect'),
//         'code'          => $code,
//     ]);

//     $data = $response->json();

//     $accessToken = $data['access_token'];

//     return response()->json([
//         'access_token' => $accessToken,
//         'expires_in'   => $data['expires_in'] ?? null,
//     ]);
// }


    public function callback(Request $request, string $provider)
    {
        $service = $this->resolveService($provider);

        $token = $service->getAccessToken($request->code);
        $profile = $service->getUserProfile($token);

        return response()->json([
            'token' => $token,
            'profile' => $profile
        ]);
    }
}