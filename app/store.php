<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['name']) && !empty($_POST['name'])) {
		$name = $_POST['name'];

		$task = new Task();

		if($task->store($name)) {
			$status["message"] = "Task stored";
		} else {
			$status["message"] = "Failed to store!";
		}
		
	} else {
		$status["message"] = "Name is required!";
	}

	echo json_encode($status);

?>