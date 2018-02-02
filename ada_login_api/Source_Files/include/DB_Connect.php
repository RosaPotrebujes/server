<?php
require_once('include/Config.php');
ChromePhp::log("page: DB_Connect.php");
class DB_Connect {
	private $conn;

	function __construct() {
		//$this->connect();
	}

	function __destruct() {
		//$this->close();
	}

	public function connect() {
		try {
			$this->conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			ChromePhp::log("Connected to database ".DB_DATABASE.".");
		} catch(PDOException $e)	{
			ChromePhp::log("Connection failed: ".$e->getMessage());
			//die("Connection failed: " . $e->getMessage());
		}
		return $this->conn;
	}

	function close() {
		if($this->conn != null) {
			$this->conn = null;
		}
		return $this->conn;
		ChromePhp:logg("Connection to database ".DB_DATABASE." is closed.");
	}
}
?>