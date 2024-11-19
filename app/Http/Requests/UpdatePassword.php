<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
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
            'password' => ['required', 'string', 'confirmed', 'min:8', 'regex:/[A-Z]', 'regex:/[a-z]', 'regex:/[0-9]', 'regex:/[@$!%*#?&]/']
        ];
    }

    public function message(): array
    {
        return [
            'password' => 'La contraseña debe tener al menos 8 caracteres',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.required' => 'La contraseña es obligatoria'
        ];
    }
}