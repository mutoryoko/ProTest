<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Transaction;

class StripeController extends Controller
{
    public function checkout(PurchaseRequest $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $itemId = $request->input('item_id');
        $item = Item::findOrFail($itemId);
        $userId = Auth::id();

        // カード支払いの場合
        if ($request->payment_method === 'card') {
            $session = Session::create([
                'payment_method_types' => ['card'],

                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ]],

                'mode' => 'payment',

                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),

                'metadata' => [
                    'item_id' => $item->id,
                    'user_id' => Auth::id(),
                    'postcode' => $request->input('shipping_postcode'),
                    'address' => $request->input('shipping_address'),
                    'building' => $request->input('shipping_building'),
                ],
            ]);

            return redirect($session->url, 303);
        }
        // コンビニ支払いの場合
        elseif($request->payment_method === 'konbini') {
            // 今回はstripeに移動する前にDB登録
            Transaction::create([
                'item_id' => $itemId,
                'buyer_id' => $userId,
                'payment_method' => 1, // コンビニ払い
                'shipping_postcode' => $request->input('shipping_postcode'),
                'shipping_address' => $request->input('shipping_address'),
                'shipping_building' => $request->input('shipping_building'),
            ]);

            $session = Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
            ]);

            return redirect($session->url, 303);
        }

        return back();
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('checkout.cancel');
        }

        try {
            $session = StripeSession::retrieve($sessionId);

            $itemId = $session->metadata->item_id ?? null;
            $item = Item::findOrFail($itemId);

            $userId = Auth::id();

            // 既に登録済みかチェック（同じsession_idで二重保存防止）
            if (Transaction::where('stripe_session_id', $sessionId)->exists()) {
                return view('checkout.success', ['item' => $item]);
            }

            $paymentMethod = $session->payment_method_types[0] ?? 'card';

            if ($paymentMethod === 'card') {
                Transaction::create([
                    'item_id' => $item->id,
                    'buyer_id' => $userId,
                    'stripe_session_id' => $sessionId,
                    'payment_method' => 2, // カード払い
                    'shipping_postcode' => $session->metadata->postcode,
                    'shipping_address' => $session->metadata->address,
                    'shipping_building' => $session->metadata->building,
                ]);
            }

            return view('checkout.success', ['item' => $item]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.cancel');
        }
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}