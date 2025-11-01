<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function show(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = $item->categories;
        $comments = Comment::where('item_id', $id)->with(['user.profile'])->get();
        $isSold = Transaction::where('item_id', $id)->exists();

        return view('detail', compact('item', 'categories', 'comments', 'isSold'));
    }

    public function sellForm()
    {
        $categories = Category::get(['id', 'name']);

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();
        $form = $request->validated();

        $form['user_id'] = $user->id;
        $form['brand'] = $request->input('brand');
        $form['item_image'] = $request->file('item_image')->store('item-images', 'public');

        $categoryIds = $form['categories'] ?? [];
        unset($form['categories']);

        $item = Item::create($form);

        $item->categories()->attach($categoryIds);

        return to_route('mypage.index');
    }
}
