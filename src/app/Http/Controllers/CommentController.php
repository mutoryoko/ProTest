<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($id);
        $commentForm = $request->validated();

        $commentForm['user_id'] = $user->id;
        $commentForm['item_id'] = $item->id;

        Comment::create($commentForm);

        return redirect()->back();
    }
}
