<?/*php
required_once 'include/DB_Functions.php'
$db = new DB_Functions();

//json response array
$response = array("error => FALSE");

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$email_taken = FALSE;
	$username_taken = FALSE;
	$error = "User with this ";

	if($db->emailTaken($email)) {
		$email_taken = TRUE;
		$error = "email";
	}
	if($db->usernameTaken($username)) {
		$username_taken = TRUE;
		if($email_taken) {
			$error = $error . " and username"
		} else {
			$error = $error . "username"
		}
	}
	$error = $error . " already exists."
	if($username_taken || $email_taken) {
		$response['error'] = TRUE;
		$response['error_msg'] = $error;
	} else {
		//addUser($name, $pass_hash, $hash_alg, $email)
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$hash_alg = "password_hash"
		$user_registered->$db->addUser($username, $hash, $hash_alg, $password);
		if($user_registered) {
			$response['error'] = FALSE;

		} else {
			$response['error'] = TRUE;
			$response['error_msg'] = "Registration failed. Please try again.";
		}
	}
}*/
?>