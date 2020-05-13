<?php

namespace App;

class Task extends Model {

	private $table = "tasks";

	public static function all()
	{
		$tasks = array();

		$model = new self();

		$query = "SELECT * FROM $model->table";
		if($sql = $model->conn->query($query)) {
			while($row = mysqli_fetch_assoc($sql)) {
				$tasks[] = $row;
			}
		}

		echo json_encode($tasks);
	}

	public function store($name) 
	{
		$query = "INSERT INTO $this->table (name) VALUES ('$name')";

		return $sql = $this->conn->query($query);

	}

	public function update($id, $name) 
	{
		$query = "UPDATE $this->table SET name = '$name' WHERE id = '$id'";

		return $sql = $this->conn->query($query);

	}

	public function delete($ids) 
	{
		$ids = implode("','", explode(',', $ids));

		$query = "DELETE FROM $this->table WHERE id IN ('".$ids."')";

		return $sql = $this->conn->query($query);

	}

	public function singleDelete($id) 
	{

		$query = "DELETE FROM $this->table WHERE id = $id";

		return $sql = $this->conn->query($query);

	}
}