<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Http;

class SocialPublisher
{

    protected $services;

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function publish(Post $post)
    {
        $account = $post->socialAccount;
        $network = $account->social_network->name; 

        $service = $this->services[$network] ?? null;

        if (!$service) {
            throw new \Exception("شبكة غير مدعومة: $network");
        }

        return $service->publishPost($account->access_token, $post);
    }

    
  
}
