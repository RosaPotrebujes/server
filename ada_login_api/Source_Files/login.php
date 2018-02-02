<?php
/*required_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if(isset($_POST['username']) && isset($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	//dobim userja iz baze
	$user = $db->getUserByUsernameAndPass($username, $password);
	if($user != false) {
		//dobili smo uporabnika
		$response["error"] = FALSE;
		$response["user"]["user_id"] = $user["user_id"];
		$response["user"]["username"] = $user["username"];
		$response["user"]["email"] = $user["email"];
		$response["user"]["description"] = $user["description"];


	} else {
		$response["error"] = TRUE;
		$response["error_msg"] = "Username or password incorrect.";
		echo json_encode($response);
	}
} else {
	$response["error"] = TRUE;
	$response["error_msg"] = "Email or password missing.";
	echo json_encode($response);
}*/
?>