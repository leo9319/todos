<?php
	require 'vendor/autoload.php';
	use App\Task;
?>

<!DOCTYPE html>
<html>
<head>
	<title>weDev Project | Todos</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link rel="stylesheet" href="public/css/main.css">
</head>
<body>

	<div id="app">
		<todo-app></todo-app>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
	<script type="text/javascript" src="public/js/app.js"></script>
	
</body>
</html>
