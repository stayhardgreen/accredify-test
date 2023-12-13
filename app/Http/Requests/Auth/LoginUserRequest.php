<?php

namespace App\Http\Requests\Auth;

use App\DTO\LoginDTO;
use App\DTO\RegisterUserDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string'
        ];
    }

    public function data(): LoginDTO
    {
        return new LoginDTO(
            $this->validated('email'),
            $this->validated('password'),
        );
    }
}
