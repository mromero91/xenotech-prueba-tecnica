<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'customer_name' => 'required|string|max:255|unique:orders,customer_name',
            'items' => 'required|array|min:1|max:50',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1|max:1000',
            'items.*.price' => 'required|numeric|min:0.01|max:999999.99',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre del cliente es obligatorio.',
            'customer_name.max' => 'El nombre del cliente no puede tener más de 255 caracteres.',
            'customer_name.unique' => 'Ya existe una orden con este nombre de cliente.',
            'items.required' => 'ERROR: La orden debe incluir al menos un item. El campo "items" es obligatorio.',
            'items.array' => 'ERROR: El campo "items" debe ser un array de productos.',
            'items.min' => 'ERROR: La orden debe tener al menos un item.',
            'items.max' => 'ERROR: La orden no puede tener más de 50 items.',
            'items.*.product_name.required' => 'ERROR: El nombre del producto es obligatorio en cada item.',
            'items.*.product_name.max' => 'ERROR: El nombre del producto no puede tener más de 255 caracteres.',
            'items.*.quantity.required' => 'ERROR: La cantidad es obligatoria en cada item.',
            'items.*.quantity.integer' => 'ERROR: La cantidad debe ser un número entero.',
            'items.*.quantity.min' => 'ERROR: La cantidad debe ser al menos 1.',
            'items.*.quantity.max' => 'ERROR: La cantidad no puede ser mayor a 1000.',
            'items.*.price.required' => 'ERROR: El precio es obligatorio en cada item.',
            'items.*.price.numeric' => 'ERROR: El precio debe ser un número.',
            'items.*.price.min' => 'ERROR: El precio debe ser mayor a 0.',
            'items.*.price.max' => 'ERROR: El precio no puede ser mayor a 999999.99.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'nombre del cliente',
            'items' => 'items',
            'items.*.product_name' => 'nombre del producto',
            'items.*.quantity' => 'cantidad',
            'items.*.price' => 'precio',

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        logger()->info('prepareForValidation', ['request' => $this->all()]);
        $this->merge([
            'customer_name' => trim($this->customer_name),
        ]);

        if ($this->has('items') && is_array($this->items)) {
            $cleanedItems = [];
            foreach ($this->items as $item) {
                $cleanedItems[] = [
                    'product_name' => trim($item['product_name'] ?? ''),
                    'quantity' => (int) ($item['quantity'] ?? 0),
                    'price' => (float) ($item['price'] ?? 0),
                ];
            }
            $this->merge(['items' => $cleanedItems]);
        }
    }
}
