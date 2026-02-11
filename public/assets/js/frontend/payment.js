/*
    Copyright AneonTech
    aneontech.help@gmail.com
*/

"use strict";

// Listen for Bootstrap collapse changes
document.addEventListener("click", function (event) {

    // Pay page step tabs
    if (event.target.matches("#razorpay-payment button")) {
        event.preventDefault();
        razorpayInit(event.target);
    }

});

// Listen for Bootstrap collapse changes
document.addEventListener("show.bs.collapse", function (event) {

    // Pay page step tabs
    if (event.target.matches("#stripe-cc")) {
        stripeClientSecret(event.target.getAttribute('data-token'));
    }

    // Pay page step tabs
    if (event.target.matches("#razorpay-cc")) {
        razorpayOrderId(event.target.getAttribute('data-token'));
    }

});

// Get Stripe clientSecret
function stripeClientSecret(action) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        action, 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                const stripeForm = document.querySelector('#stripe-payment');
                stripeForm.innerHTML = '';
                
                var div = document.createElement("div");
                div.innerHTML = `<div id="payment-element"></div>
                    <button id="submit" class="btn btn-color-2 w-100 mt-3 disabled" disabled>
                        ${langVars.pay_now}
                    </button>
                    <div id="error-message"></div>`;
       
                stripeForm.appendChild(div);
                stripeInit(result.data);
                
            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send();
}

// Stripe init
function stripeInit(token) {

    const stripe = Stripe(stripePublic);
    const options = {
        clientSecret: token,
    }
    const elements = stripe.elements( options );
    const paymentElement = elements.create( 'payment' );

    paymentElement.mount( '#payment-element' );

    const form = document.getElementById( 'stripe-payment' );

    setTimeout(() => {
        form.querySelector('#submit').classList.remove('disabled');
        form.querySelector('#submit').disabled = false;
    }, 1500);

    form.addEventListener( 'submit', async (event) => {
        event.preventDefault();

        const {error} = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: stripeReturn
            },
        });

        if (error) {
            const messageContainer = document.querySelector('#error-message');
            messageContainer.textContent = error.message;
        }
    });
}

// Get Razorpay order id
function razorpayOrderId(action) {
    const xhr = new XMLHttpRequest();
    xhr.open(
        "POST", 
        action, 
        true
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader(
        "X-CSRF-TOKEN",
        document.querySelector('meta[name="token"]').content
    );
    xhr.addEventListener("error", () => {
        toaster(langVars.error, 0);
    });
    xhr.addEventListener("abort", () => {
        toaster(langVars.cancelled, 0);
    });
    xhr.addEventListener("load", () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var result = JSON.parse(xhr.responseText);
            if (result.result) {
                const razorpayForm = document.querySelector('#razorpay-payment');
                const button = razorpayForm.querySelector('button');
                button.setAttribute('data-order', result.data);
                setTimeout(() => {
                    button.classList.remove('disabled');
                    button.disabled = false;
                }, 1500);
            } else {
                toaster(result.data, 0);
            }
        } else {
            toaster(langVars.error, 0);
        }
    });
    xhr.send();
}

// Razorpay init
function razorpayInit(button) {
    var order_id = button.getAttribute('data-order');
    var order_no = order_id.replace('order_','');
    var options = {
        "key": razorpayPublic,
        "amount": button.getAttribute('data-price'),
        "currency": button.getAttribute('data-currency'),
        "name": button.getAttribute('data-name'),
        "order_id": order_id,
        "callback_url": `${razorpayReturn}/${order_no}`,
    };
    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function (response){
        toaster(`${response.error.code}: ${response.error.description}`,0);
    });
    rzp.open();
}