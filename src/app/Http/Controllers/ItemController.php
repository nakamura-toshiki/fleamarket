<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Models\Category;
use App\Models\ItemCategory;
use App\Models\Comment;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\ProfileRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if (!$user->profile) {
                return redirect()->route('edit');
            }
        }

        $tab = $request->query('tab', 'recommend');

        $query = Item::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if (auth()->check()) {
            $query->where('user_id', '<>', auth()->user()->id);
        }

        if ($tab === 'mylist') {
            $items = auth()->check()
                ? auth()->user()->likes()->whereIn('items.id', $query->pluck('id'))->get()
                : [];
        } else {
            $items = $query->get();
        }

        return view('index', compact('items', 'tab'));
    }


    public function showMypage(Request $request)
    {
        $user = User::with('profile', 'items', 'buys')->find(auth()->id());

        $profile = $user->profile;
        if (!$profile) {
            return redirect()->route('edit');
        }

        $tab = $request->query('tab', 'sell');

        return view('profile', compact('user', 'tab'));
    }


    public function editProfile()
    {
        $userId = auth()->id();
        $profile = Profile::with('user')->where('user_id', $userId)->first();

        if (!$profile) {
            $profile = new Profile([
                'user_id' => $userId,
                'image' => null,
                'name' => null,
                'zip' => null,
                'address' => null,
                'building' => null,
            ]);
        }

        return view('edit', compact('profile'));
    }

    public function update(ProfileRequest $request, User $user)
    {
        $user = auth()->user();
        $user->update([
            'name' => $request->name,
        ]);

        $profileData = $request->only(['user_id', 'zip', 'address', 'building']);
        $profile = $user->profile ?? new Profile;

        if ($request->hasFile('image')) {
            $profileData['image'] = $request->file('image')->store('image', 'public');
        }

        $profile->fill($profileData);
        $profile->user()->associate($user);
        $profile->save();

        return redirect()->route('mypage');
    }


    public function showItem($item_id)
    {
        $item = Item::with('categories', 'likes', 'comments', 'user.profile')->find($item_id);
        $formattedPrice = number_format($item->price);

        $isLiked = auth()->check() ? $item->isLikedByUser() : false;

        $user = auth()->user();

        $comment = $item->comments()->first();
        $commentCount = $item->comments->count();

        $userId = auth()->id();
        $profile = Profile::with('user')->where('user_id', $userId)->first();

        return view('show', compact('item', 'formattedPrice', 'isLiked', 'user', 'comment', 'commentCount', 'profile'));
    }

    public function comment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'comment' => $request->input('comment'),
        ]);

        if(!auth()->user()){
            return redirect('/email/verify');
        }

        return redirect()->route('show', $item_id);
    }


    public function editAddress($item_id)
    {
        $item = Item::find($item_id);
        $user = auth()->user();
        $address = Profile::where('user_id', auth()->id())->first(['zip', 'address', 'building']);

        return view('address', compact('item', 'address'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::find($item_id);
        $profile = Profile::where('user_id', auth()->id())->firstOrFail();
        $profile->update([
            'zip' => $request->input('zip'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
        ]);

        return redirect()->route('order', ['item_id'=>$item->id]);
    }


    public function sellItem()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = auth()->user();
        $imagePath = $request->file('image')->store('image', 'public');
        $path = basename($imagePath);
        $item = Item::create([
            'user_id' => $user->id,
            'name' => $request->input('name'),
            'brand' => $request->input('brand'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'condition' => $request->input('condition'),
            'image' => $path,
        ]);

        ItemCategory::insert(
            collect($request->category_id)->map(function ($categoryId) use ($item) {
                return [
                    'item_id' => $item->id,
                    'category_id' => $categoryId,
                ];
            })->toArray()
        );

        return redirect('/');
    }
}
