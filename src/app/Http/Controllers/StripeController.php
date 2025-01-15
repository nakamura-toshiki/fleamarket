<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Buy;
use App\Models\Profile;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Charge;
use Stripe\Price;
use App\Http\Requests\PurchaseRequest;

class StripeController extends Controller
{
    public function order($item_id, User $user)
    {
        $item = Item::find($item_id);

        $formattedPrice = number_format($item->price);

        $userId = auth()->id();
        $profile = Profile::with('user')->where('user_id', $userId)->first();

        if(!$profile){
            return redirect('/email/verify');
        }

        return view('purchase', compact('item', 'formattedPrice', 'profile'));
    }


    public function storeOrder(PurchaseRequest $request, $item_id)
    {
        try{
            $buy = new Buy();
            $buy->user_id = auth()->id();
            $buy->item_id = $item_id;
            $buy->zip = $request->input('zip');
            $buy->address = $request->input('address');
            $buy->payment = $request->input('payment');
            $buy->status= 'pending';
            $buy->save();

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function checkout($item_id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::find($item_id);

        $stripePrice = Price::create([
            'unit_amount' => $item->price,
            'currency' => 'jpy',
            'product_data' => [
                'name' => $item->name,
            ],
        ]);

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price' => $stripePrice->id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success', ['item_id' => $item->id]),
            'cancel_url' => route('cancel', ['item_id' => $item->id]),
        ]);

        return redirect($checkout_session->url);
    }

    public function success($item_id)
    {
        $buy = Buy::where('item_id', $item_id)->where('status', 'pending')->first();

        if ($buy) {
            $buy->status = 'completed';
            $buy->save();

            $item = Item::findOrFail($item_id);
            $item->is_sold = true;
            $item->save();

            return redirect()->route('index')->with('message', '決済が完了しました');
        }

        return redirect()->route('index')->with('message', '決済に失敗しました');
    }

    public function cancel($item_id)
    {
        $buy = Buy::where('item_id', $item_id)->first();
        if($buy){
            $buy->delete();
        }

        return redirect()->route('index')->with('message', '決済がキャンセルされました');
    }
}
