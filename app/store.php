<?php

	require '../vendor/autoload.php';
	use App\Task;

	$status = ["message" => "success"];

	if(isset($_POST['name']) && !empty($_POST['name'])) {
		$name = $_POST['name'];

		$task = new Task();

        session_start();
        $user_id = $_SESSION['user_id'];

		if($task->store($name, $user_id)) {
			$status["message"] = "Task stored";
		} else {
			$status["message"] = "Failed to store!";
		}
		
	} else {
		$status["message"] = "Name is required!";
	}

	echo json_encode($status);

?>