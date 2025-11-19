<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipPlan; // <-- Make sure you have a Membership model
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Display the customer's home page with membership options.
     */
    public function index()
    {
        // Fetch all membership plans from the database
        $all_membership_plans = Membership::all();

        return view('customer.home', compact('all_membership_plans'));
    }

    /**
     * Handle the purchase of a membership plan.
     */
    // Old non-Razorpay method
public function activateMembership($plan_id)
{
    $user = Auth::user();
    $currentExpiry = $user->membership_expiry ? Carbon::parse($user->membership_expiry) : now();

    if ($currentExpiry->isPast()) {
        $currentExpiry = now();
    }

    $plan = MembershipPlan::find($plan_id);
    if (!$plan) {
        return redirect()->route('customer.home')->with('error', 'Invalid membership plan selected.');
    }

    if ($plan->duration_type === 'month') {
        $newExpiryDate = $currentExpiry->addMonth();
    } elseif ($plan->duration_type === 'week') {
        $newExpiryDate = $currentExpiry->addWeek();
    } else {
        $newExpiryDate = $currentExpiry->addDays($plan->duration_days ?? 0);
    }

    $user->membership_expiry = $newExpiryDate;
    $user->save();

    return redirect()->route('customer.home')->with('success', 'Thank you! Your membership is now active.');
}

    public function checkout($plan_id)
{
    // 1. Find the selected plan
    $plan = MembershipPlan::findOrFail($plan_id);
    $user = Auth::user();

    // 2. Initialize Razorpay API
    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    // 3. Create a Razorpay Order
    $order = $api->order->create([
        'receipt'         => 'order_rcptid_' . time(),
        'amount'          => $plan->price * 100, // Amount in paise
        'currency'        => 'INR',
        'payment_capture' => 1
    ]);

    $orderId = $order['id'];

    // 4. THIS IS THE CRITICAL PART: It prepares all the variables for the view
    $data = [
    'plan_name'     => $plan->name,
    'plan_id'       => $plan->id,
    'amount'        => $plan->price * 100,
    'razorpay_key'  => env('RAZORPAY_KEY'),
    'name'          => $user->name,
    'email'         => $user->email,
    'order_id'      => $orderId, // <-- Add this
];


    // 5. Return the view with the correct data array
    return view('Home.checkout_guest', $data);
}



    public function purchase(Request $request)
    {
        $planId = $request->plan_id;
        $plan = DB::table('memberships')->where('plan_id', $planId)->first();

        if (!$plan) {
            return redirect()->back()->with('error', 'Plan not found.');
        }

        $user = Auth::user();

        // Calculate membership expiry
        $durationDays = $plan->duration_days ?? 30; // fallback to 30 if not set
        $expiryDate = Carbon::now()->addDays($durationDays);

        // Update user membership
        DB::table('users')->where('id', $user->id)->update([
            'membership_expiry' => $expiryDate,
            'updated_at' => now()
        ]);

        return redirect()->route('home_customer')->with('success', 'Membership purchased successfully!');
    }
}
