@extends('Components.customer')

@section('title', 'Complete Your Payment')

@section('content')
<div class="container mt-5">
    <h2>Checkout: {{ $plan->name }}</h2>
    <p>Price: ₹{{ number_format($plan->price, 2) }}</p>
    <p>You are just one step away from activating your membership.</p>

    <button id="rzp-button1" class="btn btn-primary">Pay with Razorpay</button>

    <form id="payment-form" action="{{ route('customer.membership.verify') }}" method="POST" style="display:none;">
        @csrf
        <!-- ensure you send the plan id the controller expects -->
        <input type="hidden" name="plan_id" value="{{ $plan->id ?? $plan->plan_id }}">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        key: "{{ $razorpay_key ?? env('RAZORPAY_KEY') }}",
        amount: "{{ $amount ?? ($plan->price * 100) }}", // amount in paise (server should ideally pass $amount)
        currency: "INR",
        name: "GameSlot Membership",
        description: "Payment for {{ $plan->name }}",
        order_id: "{{ $order_id ?? '' }}", // server-created order id
        handler: function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('payment-form').submit();
        },
        prefill: {
            name: "{{ $name ?? auth()->user()->name }}",
            email: "{{ $email ?? auth()->user()->email }}"
        },
        theme: { color: "#3399cc" }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){ rzp1.open(); e.preventDefault(); }
</script>
@endsection
