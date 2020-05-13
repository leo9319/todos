<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['id']) && !empty($_POST['id'])) {

		$id = $_POST['id'];

		$task = new Task();

		if($task->singleDelete($id)) {
			$status["message"] = "Task deleted!";
		} else {
			$status["message"] = "Failed to delete!";
		}
		
	} else {
		$status["message"] = "Failed to get parameters!";
	}

	echo json_encode($status);

?>