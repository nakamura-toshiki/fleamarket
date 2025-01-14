<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class LikeController extends Controller
{
    public function toggle(Request $request, Item $item)
    {
        $user = auth()->user();

        if ($item->isLikedByUser()) {
            $item->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            $item->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        $likesCount = $item->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likesCount' => $likesCount,
        ]);
    }
}
