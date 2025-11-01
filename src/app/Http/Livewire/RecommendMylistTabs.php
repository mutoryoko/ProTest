<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Item;

class RecommendMylistTabs extends Component
{
    public $activeTab = 'recommend';

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['as' => 'tab', 'except' => 'recommend'],
    ];

    public function mount()
    {
        $this->activeTab = Auth::check() ? 'mylist' : 'recommend';

        $tabParam = strtolower(request()->query('tab', ''));

        if ($tabParam === 'mylist') {
            $this->activeTab = 'mylist';
        } else {
            $this->activeTab = 'recommend';
        }
    }

    public function render()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $itemsQuery = Item::query();
        $myListItemsQuery = null;

        if ($this->activeTab === 'recommend') {
            if ($user) {
                $itemsQuery->where('user_id', '!=', $user->id);
            }
        }
        elseif ($this->activeTab === 'mylist') {
            if ($user) {
                $myListItemsQuery = $user->likes()->where('items.user_id', '!=', $user->id);
            }
            // ゲストの場合はnullのまま
        }
        $searchableQuery = $this->activeTab === 'mylist' ? $myListItemsQuery : $itemsQuery;

        if ($searchableQuery && $this->search !== '') {
            $searchableQuery->where('item_name', 'like', '%' . $this->search . '%');
        }

        $items = $searchableQuery ? $searchableQuery->get(['items.id', 'items.item_name', 'items.item_image']) : collect();

        $soldItemIds = Transaction::pluck('item_id')->toArray();

        return view('livewire.recommend-mylist-tabs', [
            'activeTab' => $this->activeTab,
            'items' => $items,
            'soldItemIds' => $soldItemIds,
        ]);
    }
}