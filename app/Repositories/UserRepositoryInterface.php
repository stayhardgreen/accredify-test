<?php

namespace App\Repositories;

use App\DTO\LoginDTO;
use App\DTO\RegisterUserDTO;

interface UserRepositoryInterface
{
    public function create(RegisterUserDTO $registerUserDTO);
    public function login(LoginDTO $loginDTO);
}
