<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'folio' => ['required', 'string', 'max:255'],
            'computer_id' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'digits_between:10,10'],
            'start' => ['required', 'date_format:H:i', 'after_or_equal:09:00', 'before_or_equal:18:00'],
            'end' => ['required', 'date_format:H:i', 'after_or_equal:start', 'before_or_equal:18:00'],
            'total_hours' => ['required', 'numeric', 'min:0.1'],
            'total_amount' => ['required', 'numeric', 'min:1.00'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'last_name.required' => 'Agregue minimo un apellido',
            'folio.required' => 'El campo folio es obligatorio',
            'computer_id.required' => 'Seleccione un computadora',
            'phone.required' => 'El campo telefono es obligatorio',
            'start.required' => 'El campo fecha es obligatorio',
            'start.after_or_equal' => 'La hora de inicio debe ser a partir de las 09:00.',
            'start.before_or_equal' => 'La hora de inicio debe ser antes de las 18:00.',
            'end.required' => 'El tiempo de finalización es obligatorio',
            'end.after_or_equal' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'end.before_or_equal' => 'La hora de fin debe ser antes o igual a las 18:00.',
            'total_hours.required' => 'El campo horas es obligatorio',
            'total_amount.required' => 'El campo monto es obligatorio',
        ];
    }
}