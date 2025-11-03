<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Transaction $transaction)
    {
        // $user = Auth::user();

        // $sellerId = $transaction->item->user_id;

        // // ログインユーザーが購入者でも出品者でもない場合は、エラー（アクセス禁止）を返す
        // if ($user->id !== $transaction->buyer_id && $user->id !== $sellerId) {
        //     abort(403, 'Unauthorized action.');
        // }

        return view('chat');
    }
}
