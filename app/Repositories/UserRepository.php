<?php

namespace App\Repositories;

use App\DTO\LoginDTO;
use App\DTO\RegisterUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{

    public function create(RegisterUserDTO $registerUserDTO): User
    {
        $user = new User();
        $user->{User::FIELD_NAME} = $registerUserDTO->getName();
        $user->{User::FIELD_EMAIL} = $registerUserDTO->getEmail();
        $user->{User::FIELD_PWD} = Hash::make($registerUserDTO->getPassword());
        $user->save();
        return $user;
    }

    /**
     * @throws \Exception
     */
    public function login(LoginDTO $loginDTO): string
    {
        return $this->checkAuthAttempt($loginDTO->email,$loginDTO->password)
            ->generateToken($loginDTO->email,$loginDTO->password);
    }


    /**
     * @throws \Exception
     */
    private function checkAuthAttempt(string $email, string $password): self
    {
        if(!Auth::attempt(['email' => $email, 'password' => $password])){
            throw new \Exception('Email & Password does not match with our record.',401);
        }
        return $this;
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    private function generateToken(string $email, string $password): string
    {
        $userQuery = (new User())->newQuery();
        $userQuery->where(User::FIELD_EMAIL,$email);
        $user = $userQuery->first();
        return $user->createToken('auth_token')->plainTextToken;
    }
}
