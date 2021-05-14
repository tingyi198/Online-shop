<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartItem extends ApiRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id' => 'required|integer',
            'quantity' => 'required|integer',
            'product_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'cart_id.required' => '購物車編號為必填',
            'cart_id.integer' => '購物車編號必須為數字',
            'quantity.required' => '數量為必填',
            'quantity.integer' => '數量必須為數字',
            'product_id.required' => '產品編號為必填',
            'product_id.integer' => '產品編號必須為數字',
        ];
    }
}
