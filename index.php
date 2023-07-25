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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link rel="stylesheet" href="public/css/main.css">
</head>
<body>

	<div id="app">
		<todo-app></todo-app>
	</div>

    <a href="login.php?logout=true">Logout</a>

	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
	<script type="text/javascript" src="public/js/app.js"></script>
	
</body>
</html>
