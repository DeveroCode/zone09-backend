<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'total_amount' => ['numeric', 'required', 'min:1.00'],
            'total_hours' => ['numeric', 'required', 'min:0.1'],
            'end' => ['date_format:H:i', 'required', 'after_or_equal:start', 'before_or_equal:18:00'],
        ];
    }

    public function messages(): array
    {
        return [
            'total_amount.required' => 'El campo total a pagar es obligatorio',
            'total_hours.required' => 'El campo horas es obligatorio',
            'end.required' => 'El tiempo de finalización es obligatorio',
            'end.after_or_equal' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'end.before_or_equal' => 'La hora de fin debe ser antes o igual a las 18:00.',
        ];
    }
}