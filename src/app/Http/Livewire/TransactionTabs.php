<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionTabs extends Component
{
    public $tab = 'selling';

    public function mount()
    {
        $page = Request::query('page');
        if (in_array($page, ['sell', 'buy', 'in_progress'])) {
            if($page === 'sell'){
                $this->tab = 'selling';
            } elseif($page === 'buy'){
                $this->tab = 'buying';
            } else {
                $this->tab = 'in_progress';
            }
        }
    }

    public function render()
    {
        $user = Auth::user();

        $sellingItems = Item::where('user_id', $user->id)->get();

        $soldItems = Item::where('user_id', $user->id)
            ->whereHas('transaction')->get();

        $boughtItems = Item::whereHas('transaction',
            function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })->get();

        $transactionItems = $soldItems->merge($boughtItems);

        return view('livewire.transaction-tabs', [
            'sellingItems' => $sellingItems,
            'buyingItems' => $boughtItems,
            'transactionItems' => $transactionItems,
        ]);
    }
}
