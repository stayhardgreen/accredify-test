<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(
        public UserRepositoryInterface $userRepository
    )
    {}

    public function register(RegisterUserRequest $request): UserResource
    {
        return new UserResource(
            $this->userRepository->create($request->data())
        );
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $response = $this->userRepository->login($request->data());
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'data' => ['token' => $response]
            ], 200);
        }catch(\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
    }
}
