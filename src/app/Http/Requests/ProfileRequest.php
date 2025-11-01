<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_name' => 'required|string|max:20',
            'user_image' => 'nullable|image|mimes:png,jpg,jpeg',
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => 'ユーザー名を入力してください',
            'user_name.max' => 'ユーザー名は20文字以内で入力してください',
            'user_image.image' => '画像はjpg,jpeg,png形式で登録してください',
            'user_image.mimes' => '画像はjpg,jpeg,png形式で登録してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンを含めた8文字で入力してください',
            'address.required' => '住所を入力してください'
        ];
    }
}
