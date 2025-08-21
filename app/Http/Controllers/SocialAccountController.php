<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\SocialAccountRepositoryInterface;

class SocialAccountController extends Controller
{
private SocialAccountRepositoryInterface $SocialAccountRepository;
    
    public function __construct(SocialAccountRepositoryInterface $SocialAccountRepository)
    {
        $this->SocialAccountRepository = $SocialAccountRepository;
    }

    public function index(Request $request)
    {
        return $this->SocialAccountRepository->getAll($request);
    }
}
