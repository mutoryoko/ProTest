<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Rating;
use App\Models\User;
use App\Notifications\TransactionEmail;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $score = $validated['rating'];

        if ($user->id !== $transaction->buyer_id &&
            $user->id !== $transaction->item->user_id) {
            abort(403);
        }

        $ratedUserId = null;

        if ($user->id === $transaction->buyer_id) {
            $ratedUserId = $transaction->item->user_id;
        } else {
            $ratedUserId = $transaction->buyer_id;
        }

        Rating::create([
            'transaction_id' => $transaction->id,
            'rater_id' => $user->id,
            'rated_user_id' => $ratedUserId,
            'score' => $score,
        ]);

        if ($user->id === $transaction->buyer_id) {
            $transaction->update([
                'status' => 2, // 購入者評価済
            ]);
            // 出品者にメール通知
            $seller = User::find($transaction->item->user_id);
            $seller->notify(new TransactionEmail());
        } elseif ($user->id === $transaction->item->user_id) {
            $transaction->update([
                'status' => 3, // 出品者評価済
            ]);
        }

        return to_route('index')->with('message', '評価を送信しました');
    }
}
