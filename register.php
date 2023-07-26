<?php

require 'vendor/autoload.php';

use App\AuthModel;

$authModel = new AuthModel();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        // Handle registration form submission
        $submitted_username = $_POST["username"];
        $submitted_email = $_POST["email"];
        $submitted_password = $_POST["password"];
        $submitted_confirm_password = $_POST["confirm_password"];

        // Call the register method from AuthModel
        $authModel->register($submitted_username, $submitted_email, $submitted_password, $submitted_confirm_password);
    }
} elseif (isset($_GET["logout"])) {
    // Handle logout request
    $authModel->logout();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Register</title>
    <?php include_once 'partials/styles.php' ?>
</head>
<body>

<div class="pt-24">
    <div class="flex bg-white rounded-lg shadow-lg overflow-hidden mx-auto max-w-sm lg:max-w-4xl flex-row-reverse">
        <div class="hidden lg:block lg:w-1/2 bg-cover" style="background-image:url('https://images.pexels.com/photos/1109543/pexels-photo-1109543.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')"></div>
        <div class="w-full p-8 lg:w-1/2">
            <form method="post" action="register.php">
                <h2 class="text-2xl font-semibold text-gray-700 text-center">To Do App</h2>
                <p class="text-xl text-gray-600 text-center">Smart way to manage tasks!</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="border-b w-1/5 lg:w-1/4"></span>
                    <a href="#" class="text-xs text-center text-gray-500 uppercase">register with username and email</a>
                    <span class="border-b w-1/5 lg:w-1/4"></span>
                </div>
                <div class="mt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none" type="text" name="username" placeholder="Username" required>
                </div>
                <div class="mt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none" type="email" name="email" placeholder="Email address" required>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    </div>
                    <input class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none" type="password" name="password" placeholder="Password" required>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                    </div>
                    <input class="bg-gray-200 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none" type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <p class="mt-1 text-sm text-red-500">
                    <?php
                    if (isset($_SESSION['registration_error'])) {
                        echo $_SESSION['registration_error'];
                    }
                    ?>
                </p>
                <div class="mt-8">
                    <button class="bg-gray-700 text-white font-bold py-2 px-4 w-full rounded hover:bg-gray-600" type="submit" name="register">Register</button>
                </div>
            </form>
            <div class="mt-4 flex items-center justify-between">
                <span class="border-b w-1/5 md:w-1/4"></span>
                <a href="/login.php" class="text-xs text-gray-500 uppercase">back to login</a>
                <span class="border-b w-1/5 md:w-1/4"></span>
            </div>
        </div>
    </div>
</div>

<?php
// Display "Logout" link if the user is logged in
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo '<a href="login.php?logout=true">Logout</a>';
}
?>

<?php include_once 'partials/scripts.php' ?>
</body>
</html>
