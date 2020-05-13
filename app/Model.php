<?php

namespace App;

class Model{

	protected $server = "localhost";
	protected $username = "root";
	protected $password = "";
	protected $db = "todos";
	protected $conn;

	public function __construct()
	{
		try {
			$this->conn = new \mysqli($this->server, $this->username, $this->password, $this->db);
		} catch (Exception $e) {
			echo "connection failed" . $e->getMessage();
		}
	}
}