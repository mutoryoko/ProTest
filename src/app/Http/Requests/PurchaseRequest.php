<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => 'required',
            'shipping_postcode' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => 'お支払い方法を選択してください',
            'shipping_postcode.required' => '配送先を指定してください',
            'shipping_address.required' => '配送先を指定してください',
        ];
    }
}
