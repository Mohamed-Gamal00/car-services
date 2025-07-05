<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'nullable|integer|between:1,5',
            'comment' => 'required|string',
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::guard('web')->user();

        Comment::create([
            'comment' => $request->comment,
            'rate' => $request->rate,
            'user_id' => $user->id,
            'product_id' => $request->product_id
        ]);

        return redirect()->back();
    }
}
