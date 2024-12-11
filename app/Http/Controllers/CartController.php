<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\Cart;
use Carbon\Carbon;

class CartController extends Controller
{
    public function __construct()
    {
        // date_default_timezone_set(config('app.timezone', 'UTC'));
        //Carbon::setTestNow(Carbon::now(config('app.timezone')));
    }
    // Add selected number to the cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
        ]);

        $sessionId = Session::getId();

        // Check if the number is already in the cart
        $existingItem = Cart::where('number', $request->number)
            ->first();

        if ($existingItem) {
            return redirect()->back()->with('message', 'သင်ရွေးချယ်သောနံပါတ်မှာ ဝယ်ယူ၍မရပါ။');
        }

        // Add the number to the cart
        Cart::create([
            'session_id' => $sessionId,
            'number' => $request->number,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('message', 'နံပါတ် \'' . $request->number . '\' ကို သိမ်းလိုက်ပါပြီ။');
    }

    // View Cart
    public function viewCart()
    {
        $sessionId = Session::getId();
        $cartItems = Cart::where('session_id', $sessionId)->pluck('number');

        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
        ]);
    }

    // Checkout (Confirm COD order)
    public function checkout(Request $request)
    {
        $sessionId = Session::getId();

        // Remove expired cart items
        $expiredTime = Carbon::now()->subMinutes(5);
        Cart::where('session_id', $sessionId)
            ->where('created_at', '<', $expiredTime)
            ->delete();

        $cartItems = Cart::where('session_id', $sessionId)->pluck('number');

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty!'], 400);
        }

        // Process the order
        // (e.g., save in an "orders" table)

        // Clear the cart
        Cart::where('session_id', $sessionId)->delete();
        return redirect()->back()->with('message', 'Order placed successfully!');
    }
    public function getCartItems()
    {
        $sessionId = Session::getId();

        // Remove expired cart items
        $expiredTime = Carbon::now()->subMinutes(5);
        Cart::where('created_at', '<', $expiredTime)
            ->delete();

        // Fetch valid cart items
        $cartItems = Cart::where('session_id', $sessionId)
            ->get(['number', 'created_at'])
            ->map(function ($item) {

                $remainingTime = round(max(0, Carbon::now()->diffInSeconds($item->created_at->copy()->addMinutes(5))), 0);

                return [
                    'number' => $item->number,
                    'remainingTime' => $remainingTime,
                ];
            });

        return response()->json($cartItems);
    }
    public function clearCart()
    {
        $sessionId = Session::getId();
        Cart::where('session_id', $sessionId)->delete();
        return redirect()->back()->with('message', 'နံပါတ်များအားလုံး ဖျက်လိုက်ပါပြီ။');
    }
    public function clearCartItem(Request $request)
    {

        $sessionId = Session::getId();
        Cart::where('session_id', $sessionId)
            ->where('number', $request->number)
            ->delete();
        return redirect()->back()->with('message', 'နံပါတ်ကို ဖျက်လိုက်ပါပြီ။');
    }
}
