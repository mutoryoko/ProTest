<div>
    <div class="tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="{{ $tab === 'selling' ? 'active' : '' }} sell-tab">
            出品した商品
        </a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="{{ $tab === 'buying' ? 'active' : '' }} buy-tab">
            購入した商品
        </a>
        <div class="tab-badge">
            <a href="{{ route('mypage.index', ['page' => 'in_progress']) }}" class="{{ $tab === 'in_progress' ? 'active' : '' }} progress-tab">
                取引中の商品
                @if($totalUnreadCount > 0)
                    <span class="total-badge">{{ $totalUnreadCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <div class="tab__content mt-4">
        @if ($tab === 'selling')
            @forelse ($sellingItems as $sellingItem)
                <div class="item-card">
                    <div class="item-image__wrapper">
                        <a href="{{ route('detail', ['item_id' => $sellingItem->id]) }}">
                            <img class="item-image" src="{{ asset('storage/'.$sellingItem->item_image) }}" alt="商品画像">
                        </a>
                    </div>
                    <p class="item-name">{{ $sellingItem->item_name }}</p>
                </div>
            @empty
                <p>出品した商品がありません。</p>
            @endforelse
        @elseif ($tab === 'buying')
            @forelse ($buyingItems as $buyingItem)
                <div class="item-card">
                    <div class="item-image__wrapper">
                        <a href="{{ route('detail', ['item_id' => $buyingItem->id]) }}">
                            <img class="item-image" src="{{ asset('storage/'.$buyingItem->item_image) }}" alt="商品画像">
                        </a>
                    </div>
                    <p class="item-name">{{ $buyingItem->item_name }}</p>
                </div>
            @empty
                <p>購入した商品がありません。</p>
            @endforelse
        @elseif ($tab === 'in_progress')
            @forelse ($transactionItems as $transactionItem)
                @php
                    $unreadCount = $transactionItem->transaction->chat->unreadCountFor(Auth::user());
                @endphp
                <div class="item-card">
                    <div class="item-image__wrapper">
                        <a href="{{ route('chat.show', ['transaction' => $transactionItem->transaction->id]) }}">
                            @if($unreadCount > 0)
                                <span class="badge">{{ $unreadCount }}</span>
                            @endif
                            <img class="item-image" src="{{ asset('storage/'.$transactionItem->item_image) }}" alt="商品画像">
                        </a>
                    </div>
                    <p class="item-name">{{ $transactionItem->item_name }}</p>
                </div>
            @empty
                <p>取引中の商品がありません。</p>
            @endforelse
        @endif
    </div>
</div>
