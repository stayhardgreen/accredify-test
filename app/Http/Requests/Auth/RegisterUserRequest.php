<?php

namespace App\Http\Requests\Auth;

use App\DTO\RegisterUserDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email|email',
            'password' => ['required','string',Password::min(5)->mixedCase()->letters()->numbers()->symbols()->uncompromised()]
        ];
    }

    public function data(): RegisterUserDTO
    {
        return new RegisterUserDTO(
            $this->validated('name'),
            $this->validated('email'),
            $this->validated('password'),
        );
    }
}
