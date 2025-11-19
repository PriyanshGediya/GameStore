<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class MembershipController extends Controller
{
    /**
     * Show checkout page and create Razorpay order
     */
    public function checkout(Request $request, $plan_id = null)
    {
        // Accept either route param or query/form param
        $planId = $plan_id ?? $request->plan_id;

        // Try both id and plan_id columns to be safe
        $plan = DB::table('memberships')
            ->where('id', $planId)
            ->orWhere('plan_id', $planId)
            ->first();

        if (!$plan) {
            return redirect()->route('home_customer')->with('error', 'Membership plan not found.');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login_page')->with('error', 'Please log in to purchase a membership.');
        }

        // create Razorpay order
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $amountPaise = (int) round($plan->price * 100); // paise

        try {
            $order = $api->order->create([
                'receipt' => 'membership_rcpt_' . time(),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: '.$e->getMessage());
            return redirect()->route('home_customer')->with('error', 'Unable to create payment order. Try again later.');
        }

        $orderId = $order['id'] ?? null;
        if (!$orderId) {
            return redirect()->route('home_customer')->with('error', 'Unable to create payment order. Try again.');
        }

        // pass variables to blade - ensure blade uses these variable names
        return view('Home.checkout_guest', [
            'plan' => $plan,
            'plan_id' => $plan->id ?? $plan->plan_id,
            'order_id' => $orderId,
            'amount' => $amountPaise,
            'razorpay_key' => env('RAZORPAY_KEY'),
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Verify Razorpay payment and update user's membership_expiry
     */
    public function verifyPayment(Request $request)
    {
        Log::info('Membership verifyPayment called', $request->all());

        $user = Auth::user();
        if (!$user) {
            Log::warning('verifyPayment: unauthenticated attempt');
            return redirect()->route('home_customer')->with('error', 'You must be logged in.');
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $attributes = [
            'razorpay_order_id'   => $request->input('razorpay_order_id'),
            'razorpay_payment_id' => $request->input('razorpay_payment_id'),
            'razorpay_signature'  => $request->input('razorpay_signature'),
        ];

        try {
            $api->utility->verifyPaymentSignature($attributes);
        } catch (SignatureVerificationError $e) {
            Log::error('Razorpay signature verification failed', ['error' => $e->getMessage(), 'attributes' => $attributes]);
            return redirect()->route('home_customer')->with('error', 'Payment verification failed: invalid signature.');
        } catch (\Exception $e) {
            Log::error('Razorpay verify exception', ['error' => $e->getMessage()]);
            return redirect()->route('home_customer')->with('error', 'Payment verification failed.');
        }

        // Find the plan (again be flexible with id/plan_id)
        $planId = $request->input('plan_id');
        $plan = DB::table('memberships')
            ->where('id', $planId)
            ->orWhere('plan_id', $planId)
            ->first();

        if (!$plan) {
            Log::error('verifyPayment: membership plan not found', ['plan_id' => $planId]);
            return redirect()->route('home_customer')->with('error', 'Membership plan not found.');
        }

        // Determine how to add duration: support duration_type (month/week/days)
        $currentExpiry = ($user->membership_expiry && Carbon::now()->lt($user->membership_expiry))
            ? Carbon::parse($user->membership_expiry)
            : Carbon::now();

        // default duration_days fallback
        $durationDays = (int) ($plan->duration_days ?? 30);
        $durationType = $plan->duration_type ?? 'days';

        if ($durationType === 'month') {
            // if duration_days stores months, use addMonths
            // if duration_days is total days, fallback to addDays
            $newExpiry = $currentExpiry->addDays($durationDays);
        } elseif ($durationType === 'week') {
            $newExpiry = $currentExpiry->addDays($durationDays); // if you store weeks, convert: addWeeks($plan->duration_days)
        } else {
            // 'days' or unknown
            $newExpiry = $currentExpiry->addDays($durationDays);
        }

        // Save membership_expiry as datetime
        $user->membership_expiry = $newExpiry;
        $user->save();

        Log::info('Membership updated for user', ['user_id' => $user->id, 'new_expiry' => $newExpiry->toDateTimeString()]);

        return redirect()->route('home_customer')->with('success', "Membership purchased successfully! Valid until {$newExpiry->format('d M Y')}");
    }
}
