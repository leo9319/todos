<?php

    require 'vendor/autoload.php';
    use App\AuthModel;
    $authModel = new AuthModel();

    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        // Redirect to the login page if the user is not logged in
        header("location: login.php");
        exit;
    }

    if (isset($_GET["logout"])) {
        // Handle logout request
        $authModel->logout();
    }


?>

<!DOCTYPE html>
<html>
<head>
	<title>Portfolio | Todos</title>
	<link rel="stylesheet" href="public/css/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

	<div id="app">
        <nav-bar></nav-bar>
		<todo-app></todo-app>
	</div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
	<script type="text/javascript" src="public/js/app.js"></script>
	
</body>
</html>
