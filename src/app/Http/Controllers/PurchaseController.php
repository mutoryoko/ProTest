<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    private function getItemAndProfile($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();
        $profile = $user->profile;

        return compact('item', 'user', 'profile');
    }

    public function index(Request $request, string $id)
    {
        $data = $this->getItemAndProfile($id);

        $paymentMethods = [
            'konbini' => 'コンビニ支払い',
            'card' => 'カード支払い',
        ];

        // クエリパラメータから選択された支払い方法のキーを取得
        $selectedPaymentKey = $request->query('payment_method');
        // キーに対応する表示名を取得
        $selectedPaymentName = $paymentMethods[$selectedPaymentKey] ?? null;

        $isSold = Transaction::where('item_id', $id)->exists();

        return view('purchase', array_merge($data, compact(
            'isSold',
            'paymentMethods',
            'selectedPaymentKey',
            'selectedPaymentName'
        )));
    }

    public function edit(string $id)
    {
        $data = $this->getItemAndProfile($id);

        return view('address', $data);
    }

    //配送先変更処理
    public function update(AddressRequest $request, string $id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        $updateData = $request->validated();
        /** @var \App\Models\User $user */
        $user->profile()->updateOrCreate([], $updateData);
        // データベースの更新後、メモリ上の$userモデルが持つprofileリレーションを再読み込み
        $user->load('profile');

        return to_route('purchase', ['item_id' => $item->id]);
    }
}
