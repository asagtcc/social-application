<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;

class PostController extends Controller
{
private PostRepositoryInterface $PostRepository;
private SocialAccountRepositoryInterface $SocialAccountRepository;
    
    public function __construct(
        SocialAccountRepositoryInterface $SocialAccountRepository,
        PostRepositoryInterface $PostRepository
        )
    {
        $this->PostRepository = $PostRepository;
        $this->SocialAccountRepository = $SocialAccountRepository;
    }

    public function index(Request $request, $account, $status)
    {
        $SocialAccount = $this->SocialAccountRepository->getByAccountId($account);
        if (!$SocialAccount) {
            return response()->json([
                'message' => 'Social Account not found'
            ], 400);
        }
        $posts = $this->PostRepository->getAllByAccount($SocialAccount->id, $status, auth()->id());
        return PostResource::collection($posts);
        //return response()->json($posts);
    }

    public function store(Request $request, $account)
    {
           $SocialAccount = $this->SocialAccountRepository->getByAccountId($account);
        if (!$SocialAccount) {
            return response()->json([
                'message' => 'Social Account not found'
            ], 400);
        }

        $data = $request->validate([
            'type'         => 'required|in:post,reel,story',
            'content'      => 'required|string',
            'published_at' => 'required|date',
            'photos'       => 'nullable|array', 
            'photos.*'     => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240', 
        ]);
        $data['user_id'] = auth()->id();
        $data['social_account_id'] = $SocialAccount->id;
        $post = $this->PostRepository->create($data);

        return response()->json($post, 201);
    }
}
