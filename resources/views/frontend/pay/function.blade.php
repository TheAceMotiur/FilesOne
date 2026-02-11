@if (!$plan->free && $stripe->status == 1)
    <script src="https://js.stripe.com/v3/"></script>
@endif
@if (!$plan->free && $razorpay->status == 1)
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
{!! library('/assets/js/frontend/payment.js') !!}
<script>
    @if (!$plan->free && $stripe->status == 1)
        /* Init Stripe gateway */
        var stripePublic = "{{ $stripe['public'] }}";
        var stripeReturn = "{{ url()->full() }}/stripe";
    @endif

    @if (!$plan->free && $razorpay->status == 1)
        /* Init Razorpay gateway */
        var razorpayPublic = "{{ $razorpay['public'] }}";
        var razorpayReturn = "{{ url()->full() }}/razorpay";
    @endif
</script>