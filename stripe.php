<?php
// Require the Stripe PHP library
require_once('vendor/autoload.php');

use App\User;

session_start();

$user = new User();
$is_premium = $_SESSION['is_premium'];
$_SESSION['card_error'] = '';

if($is_premium) {
    header("location: index.php");
    exit;
}

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
            'amount' => 1999,
            'currency' => 'usd',
            'source' => $token,
            'description' => 'To do ap one-time payment',
        ]);

        // Payment successful, you can handle the success scenario here
        session_start();
        $user_id = $_SESSION['user_id'];
        $user = new User();
        $user->set_premium($user_id);
        $_SESSION['is_premium'] = 1;
        header("Location: index.php");
        exit;
    } catch (\Stripe\Exception\CardException $e) {
        // Payment failed due to card error
        $_SESSION['card_error'] = 'Card declined: ' . $e->getMessage();
    } catch (Exception $e) {
        // Other error occurred
        $_SESSION['card_error'] = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment Integration</title>
    <?php include_once 'partials/styles.php' ?>
    <!-- Include Stripe.js library -->
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<div class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center ">
        <div class="bg-white rounded-lg shadow-lg p-8 w-11/12 md:w-4/12">
            <h1 class="text-red-500 font-bold mb-4 text-2xl text-center opacity-50">Payment Information</h1>

            <div class="container">
                <div class="flex flex-col space-y-4">
                    <div class="w-full mx-auto">
                        <form action="" method="post" id="payment-form">
                            <div>
                                <label>Card Number</label>
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                            </div>
                            <button class="mt-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs w-full" type="submit">Pay Now</button>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" class="text-red-500 text-xs" role="alert"></div>
                            <div class="text-xs text-red-500">
                                <?php
                                if (isset($_SESSION['card_error'])) {
                                    echo $_SESSION['card_error'];
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<?php include_once 'partials/scripts.php' ?>

<script>
    // Create a Stripe client
    let stripe = Stripe('<?php echo $stripePublicKey; ?>');

    // Create an instance of Elements
    let elements = stripe.elements();

    // Create a card Element and mount it on the card-element div
    let card = elements.create('card');

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
    let form = document.getElementById('payment-form');
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
        let form = document.getElementById('payment-form');
        let hiddenInput = document.createElement('input');
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
