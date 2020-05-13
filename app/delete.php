<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['ids']) && !empty($_POST['ids'])) {

		$ids = $_POST['ids'];

		$task = new Task();

		if($task->delete($ids)) {
			$status["message"] = "Tasks deleted!";
		} else {
			$status["message"] = "Failed to delete!";
		}
		
	} else {
		$status["message"] = "Failed to get parameters!";
	}

	echo json_encode($status);

?>