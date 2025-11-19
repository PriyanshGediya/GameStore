@extends('Components.customer')

@section('title', 'Secure Checkout')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #1a1a1d, #121217);
        font-family: 'Poppins', sans-serif;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
        color: #f0f0f0;
    }

    .payment-container {
        width: 100%;
        max-width: 480px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        backdrop-filter: blur(15px);
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
    }

    .payment-header {
        padding: 30px 25px;
        text-align: center;
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .payment-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
    }

    .payment-header p {
        margin-top: 8px;
        color: #aaa;
        font-size: 0.9rem;
    }

    .payment-body {
        padding: 25px 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .order-summary h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #ccc;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 8px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        font-size: 1rem;
        color: #e0e0e0;
        margin-bottom: 10px;
    }

    .total-amount {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .total-amount .label {
        font-weight: 600;
        font-size: 1.2rem;
        color: #fff;
    }

    .total-amount .amount {
        font-weight: 700;
        font-size: 1.7rem;
        background: #ffffffff;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .payment-footer {
        padding: 25px 30px;
        background: rgba(255, 255, 255, 0.05);
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    #rzp-button1 {
        width: 100%;
        padding: 16px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #000000ff;
        background: #fff;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    #rzp-button1:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.4);
    }

    .secure-info {
        font-size: 0.85rem;
        color: #aaa;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
    }

    .secure-info svg {
        stroke: #528FF0;
    }
</style>
<center>
<div class="payment-container" center>
    <div class="payment-header">
        <h2>Epics Game Store Checkout</h2>
        <p>Complete your purchase securely</p>
    </div>

    <div class="payment-body">
        <div class="order-summary">
            <h3>Order Summary</h3>
            @foreach($cartItems as $item)
<div class="summary-item">
    <span>{{ $item->name }}</span>
    <span>₹{{ number_format($item->price, 2) }}</span>
</div>
@endforeach

        </div>

        <div class="total-amount">
            <span class="label">Total to Pay</span>
            <span class="amount">₹{{ number_format($amount, 2) }}</span>
        </div>
    </div>

    <div class="payment-footer">
        <form action="{{ route('customer.cart.callback') }}" method="POST" id="razorpay-form">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            
            <button type="button" id="rzp-button1">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Pay Securely
            </button>
        </form>
        <p class="secure-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 1v2m0 18v2m11-11h-2M3 12H1m17.66-5.66l-1.42 1.42M6.76 17.24l-1.42 1.42m0-12.12l1.42 1.42M17.24 17.24l1.42 1.42"/></svg>
            100% Secure Payments powered by Razorpay
        </p>
    </div>
</div>  
</center>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{ $razorpayKey }}",
    "amount": "{{ $amount * 100 }}",
    "currency": "INR",
    "name": "GameStore",
    "description": "Purchase Games",
    "order_id": "{{ $orderId }}",
    "handler": function (response){
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.getElementById('razorpay-form').submit();
    },
    "theme": {
        "color": "#528FF0"
    }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
@endsection
