<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_image' => 'required|file|image|mimes:png,jpg,jpeg',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'condition' => 'required',
            'item_name' => 'required|string|max:255',
            'bland' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'item_image.required' => '画像をアップロードしてください',
            'item_image.file' => '画像をアップロードしてください',
            'item_image.image' => '画像はjpg,jpeg,png形式で登録してください',
            'item_image.mimes' => '画像はjpg,jpeg,png形式で登録してください',
            'categories.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'item_name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で設定してください',
        ];
    }
}
