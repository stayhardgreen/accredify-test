<?php

namespace App\DTO;

class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
