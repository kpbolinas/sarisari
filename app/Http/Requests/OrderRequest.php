<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'items' => 'required',
            'items.*.item' => 'required',
            'items.*.item.id' => 'required|exists:products,id',
            'items.*.item.name' => 'required|regex:/^[a-zA-Z0-9-_.\s]+$/',
            'items.*.item.price' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'total_price' => 'required|numeric',
        ];
    }
}
