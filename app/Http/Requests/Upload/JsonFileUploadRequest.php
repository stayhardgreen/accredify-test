<?php

namespace App\Http\Requests\Upload;

use App\DTO\JsonFileUploadDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class JsonFileUploadRequest extends FormRequest
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
            'file' => 'required|file|mimetypes:application/json,text/plain'
        ];
    }

    public function data(): JsonFileUploadDTO
    {
        return new JsonFileUploadDTO(
            Auth::user()->id,
            $this->validated('file'),
        );
    }
}
