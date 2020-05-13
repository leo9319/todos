<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['name']) && !empty($_POST['name'])) {

		$id = $_POST['id'];
		$name = $_POST['name'];

		$task = new Task();

		if($task->update($id, $name)) {
			$status["message"] = "Task updated";
		} else {
			$status["message"] = "Failed to store!";
		}
		
	} else {
		$status["message"] = "Id and name is required!";
	}

	echo json_encode($status);

?>