<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatMessageRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function show(Transaction $transaction, Request $request)
    {
        $user = Auth::user();

        if ($user->id !== $transaction->buyer_id && $user->id !== $transaction->item->user_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->id === $transaction->buyer_id) {
            $partner = $transaction->item->user;
        } else {
            $partner = $transaction->buyer;
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

        $otherTransactionItems = $transactionItems
            ->where('id', '!=', $transaction->item->id);

        $chat = Chat::firstOrCreate(['transaction_id' => $transaction->id,]);

        $messages = $chat->messages()->orderBy('created_at')->get();

        $edit_message_id = $request->query('edit_message_id');

        if ($edit_message_id) {
            $message_to_edit = Message::find($edit_message_id);
            if (!$message_to_edit || $message_to_edit->sender_id !== $user->id) {
                abort(403);
            }
        }

        return view('chat', compact(
            'user',
            'partner',
            'transaction',
            'otherTransactionItems',
            'messages',
            'edit_message_id'
        ));
    }

    public function store(ChatMessageRequest $request, Transaction $transaction)
    {
        $user = Auth::user();

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat-images', 'public');
        } else {
            $imagePath = null;
        }

        $chat = Chat::where('transaction_id', $transaction->id)->firstOrFail();

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'body' => $validated['body'],
            'image' => $imagePath,
        ]);

        $chat->last_message_id = $message->id;
        $chat->last_message_at = $message->created_at;
        $chat->save();

        return to_route('chat.show', ['transaction' => $transaction->id]);
    }

    public function update(ChatMessageRequest $request, Message $message)
    {
        if (auth()->id() !== $message->sender_id) {
            abort(403);
        }

        $validated = $request->validated();
        $message->update($validated);

        return to_route('chat.show', [
            'transaction' => $message->chat->transaction_id
        ])->with('message', 'メッセージを更新しました');
    }

    public function destroy(Request $request, Message $message)
    {
        if (auth()->id() !== $message->sender_id) {
            abort(403);
        }

        if ($message->image) {
            if (Storage::disk('public')->exists($message->image)) {
                Storage::disk('public')->delete($message->image);
            }
        }
        $message->delete();

        return to_route('chat.show',[
            'transaction' => $message->chat->transaction_id
        ])->with('message', 'メッセージを削除しました');
    }
}
