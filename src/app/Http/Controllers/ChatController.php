<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        if ($user->id !== $transaction->buyer_id && $user->id !== $transaction->item->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $relations = ['transaction', 'user'];

        $soldItems = Item::with($relations)
            ->where('user_id', $user->id)
            ->whereHas('transaction')
            ->get();

        $boughtItems = Item::with($relations)
            ->whereHas('transaction', function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })
            ->get();

        $transactionItems = $soldItems->merge($boughtItems)->unique('id');

        if ($user->id === $transaction->buyer_id) {
            $partner = $transaction->item->user;
        } else {
            $partner = $transaction->buyer;
        }

        return view('chat', compact(
            'transaction',
            'partner',
            'transactionItems'
        ));
    }

    public function store()
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
