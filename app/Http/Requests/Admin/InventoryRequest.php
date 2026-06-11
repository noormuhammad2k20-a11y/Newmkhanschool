<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_code' => 'required|string|max:50|unique:inventory,asset_code,' . $this->route('inventory'),
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category' => 'required|string|max:50',
            'quantity' => 'required|integer|min:0',
            'condition_status' => 'required|string|in:Good,Fair,Poor,Broken',
            'unit' => 'nullable|string|max:50',
            'min_stock_alert' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
        ];
    }
}
