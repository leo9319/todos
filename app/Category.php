<?php

namespace App;

class Category extends Model {

	private $table = "categories";

	public static function all($user_id)
	{
		$categories = array();

		$model = new self();

		$query = "SELECT * FROM $model->table WHERE user_id = '$user_id'";
		if($sql = $model->conn->query($query)) {
			while($row = mysqli_fetch_assoc($sql)) {
                $categories[] = $row;
			}
		}

		echo json_encode($categories);
	}

	public function store($name, $user_id)
	{
		$query = "INSERT INTO $this->table (name, user_id) VALUES ('$name', '$user_id')";

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