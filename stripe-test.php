<?php
// Require the Stripe PHP library
require_once('vendor/autoload.php');

// Set your Stripe API keys
$stripeSecretKey = 'sk_test_51NXr0UBlJ4e0Rc9h0J3ptxH9iKkl269gx7pwKYvZEycqErT3HadoL8P00rToWcMpWCCudADv576Rr7alC0Hd36o600d1j4poXI';
$stripePublicKey = 'pk_test_51NXr0UBlJ4e0Rc9h3Janq75vNjCLv2EpNjLJq2IObdhu3eWfq3Yq8zCdafbc55yIvVxWOeeTxyrFwZZAYAvQPTev00p4TiiviX';

\Stripe\Stripe::setApiKey($stripeSecretKey);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the payment token from the submitted form (Stripe.js token)
    $token = $_POST['stripeToken'];

    try {
        // Create a charge using the token
        $charge = \Stripe\Charge::create([
            'amount' => 1999, // Amount in cents (e.g., $19.99)
            'currency' => 'usd',
            'source' => $token,
            'description' => 'Example one-time payment',
        ]);

        // Payment successful, you can handle the success scenario here
        echo 'Payment successful! Charge ID: ' . $charge->id;
    } catch (\Stripe\Exception\CardException $e) {
        // Payment failed due to card error
        echo 'Card declined: ' . $e->getMessage();
    } catch (Exception $e) {
        // Other error occurred
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment Integration</title>
    <!-- Include Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<h1>Stripe Payment Integration Example</h1>
<form action="" method="post" id="payment-form">
    <div>
        <label>Card Number</label>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>
    </div>
    <button type="submit">Pay Now</button>
    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
</form>

<script>
    // Create a Stripe client
    var stripe = Stripe('<?php echo $stripePublicKey; ?>');

    // Create an instance of Elements
    var elements = stripe.elements();

    // Create a card Element and mount it on the card-element div
    var card = elements.create('card');

    card.mount('#card-element');

    // Handle real-time validation errors from the card Element
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Disable the submit button to prevent multiple submissions
        form.querySelector('button').disabled = true;

        // Create a token using the card Element
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;

                // Enable the submit button again
                form.querySelector('button').disabled = false;
            } else {
                // Send the token to the server
                stripeTokenHandler(result.token);
            }
        });
    });

    // Submit the form with the token ID
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
</script>
</body>
</html>
