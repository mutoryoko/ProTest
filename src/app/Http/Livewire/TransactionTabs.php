<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionTabs extends Component
{
    public $tab = 'selling';

    public function mount()
    {
        $page = Request::query('page');
        if (in_array($page, ['sell', 'buy'])) {
            $this->tab = $page === 'sell' ? 'selling' : 'buying';
        }
    }

    public function render()
    {
        $user = Auth::user();

        $sellingItems = Item::where('user_id', $user->id)->get();

        $boughtItemIds = Transaction::where('buyer_id', $user->id)->pluck('item_id');
        $buyingItems = Item::whereIn('id', $boughtItemIds)->get();

        return view('livewire.transaction-tabs', [
            'sellingItems' => $sellingItems,
            'buyingItems' => $buyingItems,
        ]);
    }
}
