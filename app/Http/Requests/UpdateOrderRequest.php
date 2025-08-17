<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
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
            'status' => [
                'sometimes',
                'string',
                Rule::in(OrderStatus::cases()),
            ],
            'customer_name' =>
                'sometimes|string|max:255|unique:orders,customer_name,' . $this->route('order'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.in' => 'El estado debe ser uno de los valores permitidos.',
            'customer_name.max' => 'El nombre del cliente no puede tener más de 255 caracteres.',
            'customer_name.unique' => 'Ya existe una orden con este nombre de cliente.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'status' => 'estado',
            'customer_name' => 'nombre del cliente',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('customer_name')) {
            $this->merge([
                'customer_name' => trim($this->customer_name),
            ]);
        }
    }
}
