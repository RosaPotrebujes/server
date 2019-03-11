<?php

$out = null;

$rez = exec('"C:\Program Files (x86)\Python27\python" "C:\wamp64\www\ada_login_api\Source_Files\resources\dejavu\dip_detect_recorded_files.py" '.'1518987260883.3gp 2>&1',$out,$rv);
file_put_contents("somefile.txt",$out);
if($rez) {
	$str = "";
	foreach($out as $item) {
	    $str = $str ."</br>". $item;
	}
	echo $str;
	#echo implode(',',$out);
	#echo "rez is true. out: ".$out[0]." size ".count($out);
} else {
	echo "wasd";
}

/*required_once 'include/DB_Functions.php'
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