<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;


class SignUpUserRequest extends FormRequest
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
            "first_name" => "required|string|max:30|",
            "last_name" => "required|string|max:30|",
            "email" => "required|email|max:30|unique:users",
            "address" => "required|string|",
            "phone_number" => "nullable|string|max:10|",
            "password" => "required|string|min:8|max:20|confirmed",
        ];
    }
}
