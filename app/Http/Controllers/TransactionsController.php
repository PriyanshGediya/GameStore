<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class TransactionsController extends Controller
{
    public function cart_view(){
    $user = Auth::user();
    $carts = Cart::where('user_id', $user->id)->get();

    $isMember = $user->membership_expiry && $user->membership_expiry > Carbon::now();

    $totalPrice = 0;
    foreach($carts as $cp){
        $gamePrice = $cp->game->game_price;
        if ($isMember) {
            $gamePrice *= 0.70; // apply 30% discount
        }
        $totalPrice += $cp->qty * $gamePrice;
    }

    return view('Home.cart', compact('carts', 'totalPrice', 'isMember'));
}


    public function add_to_cart(Request $request){
        $request->validate([
            'qty' => 'required'
        ]);

        $user_id = Auth::user()->id;

        DB::table('carts')->insert([
            'user_id' => $user_id,
            'game_id' => $request->game_id,
            'qty' => $request->qty
        ]);

        return redirect()->route('customer.cart.view');
    }

    public function delete_game_in_cart(Request $request){
        Cart::where('id', $request->id)->delete();
        return redirect()->route('customer.cart.view');
    }

    /**
     * NEW METHOD: Add this function to your controller.
     * This method handles the "Clear Cart" functionality.
     */
    public function delete_cart()
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Find all cart items belonging to this user and delete them
        Cart::where('user_id', $userId)->delete();

        // Redirect back to the cart page with a success message
        return redirect()->route('customer.cart.view')->with('success', 'Your cart has been successfully cleared.');
    }


    // Step 1: Create Razorpay order and redirect to checkout
    public function purchase(Request $request){
    $user = Auth::user();
    $carts = Cart::where('user_id', $user->id)->get();

    // Determine membership status first
    $isMember = $user->membership_expiry && $user->membership_expiry > Carbon::now();

    $cartItems = $carts->map(function($c) use ($isMember) {
        $price = $c->game->game_price;
        if ($isMember) $price *= 0.70;
        return (object)[
            'name' => $c->game->game_name,
            'price' => $price,
        ];
    });

    if($carts->isEmpty()){
        return redirect()->route('customer.cart.view')->with('error', 'Cart is empty.');
    }

    $amount = 0;
    foreach($carts as $c){
        $gamePrice = $c->game->game_price;
        if ($isMember) $gamePrice *= 0.70;
        $amount += $c->qty * $gamePrice;
    }

    if ($amount < 1) {
        return redirect()->back()->with('error', 'The total amount must be at least ₹1.00 to proceed.');
    }
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $orderData = [
                'receipt' => 'rcptid_' . time(),
                'amount' => (int)round($amount * 100), // in paise
                'currency' => 'INR',
                'payment_capture' => 1
            ];

            $razorpayOrder = $api->order->create($orderData);
            $orderId = $razorpayOrder['id'];

            if(!$orderId){
                return redirect()->back()->with('error', 'Unable to create order with Razorpay.');
            }

            return view('payment.checkout', [
                'orderId' => $orderId,
                'amount' => $amount,
                'razorpayKey' => env('RAZORPAY_KEY'),
                'user' => $user,
                'cartItems' => $cartItems,
            ]);

        } catch (\Exception $e) {
            \Log::error('Razorpay Error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Unable to create order with Razorpay: ' . $e->getMessage());
        }
    }


    // Step 2: Razorpay callback to verify and store transactions
    public function callback(Request $request){
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);

            $user = Auth::user();
            $carts = Cart::where('user_id', $user->id)->get();

            $isMember = false;
            if ($user->membership_expiry && $user->membership_expiry > Carbon::now()) {
                $isMember = true;
            }

            foreach($carts as $c){
                $gamePrice = $c->game->game_price;
                if ($isMember) {
                    $gamePrice *= 0.70;
                }

                Transaction::create([
                    'transaction_user_id' => $c->user_id,
                    'transaction_game_id' => $c->game_id,
                    'qty' => $c->qty,
                    'final_price' => $c->qty * $gamePrice,
                    'transaction_date_time' => Carbon::now()
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            return redirect()->route('customer.history')->with('success', 'Payment successful and purchase recorded.');
        } catch (\Exception $e) {
            return redirect()->route('customer.cart.view')->with('error', 'Payment verification failed: '.$e->getMessage());
        }
    }

    public function history_index(){
        $user_id = Auth::user()->id;
        $historyTr = Transaction::where('transaction_user_id', $user_id)
                                ->latest('transaction_date_time')
                                ->get()
                                ->groupBy('transaction_date_time');

        $collapse = 0;

        foreach($historyTr as $ht){
            $totalPrice = 0;
            $totalQty = 0;
            foreach ($ht as $ht1) {
                $totalPrice += $ht1->final_price;
                $totalQty += $ht1->qty;
            }
            $ht->totalPrice = $totalPrice;
            $ht->totalQty = $totalQty;
            $collapse += 1;
            $ht->collapse = $collapse;
        }

        return view('Home.history', compact('historyTr'));
    }
}
