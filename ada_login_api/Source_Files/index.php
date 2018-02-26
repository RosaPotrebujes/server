<?php
include ('resources/chromephp/ChromePhp.php');
ChromePhp::log('page: index.php');

require_once('include/User_Functions.php');
require_once('include/DB_Handler.php');

set_time_limit(60);
$db_handler = new DB_Handler();
/*//send friend request
//user id: 1, username = user1
//user id: 19, new_user_1
$fun = "sendFriendRequest";
$params = array();
$params["sender_id"] = 19;
$params["receiver_username"] = "user1";
$result = json_decode($db_handler->select_function($fun, $params),true);
ChromePhp::log("Function: ".$fun."\n Parameters: ",$params, "\nSuccess: ".$result["success"].". Message: ".$result["message"]);*/

/*//send friend request
$fun = "sendFriendRequest";
$params = array();
$params["sender_id"] = 1;
$params["receiver_username"] = "userfab";
$result = json_decode($db_handler->select_function($fun, $params),true);
ChromePhp::log("Function: ".$fun."\n Parameters: ",$params, "\nSuccess: ".$result["success"].". Message: ".$result["message"]);*/

/*//toggle favourite post
$fun = "toggleFavouritePost";
$params = array();

$params["post_id"] = 1;
$params["user_id"] = 2;
$ts = new DateTime('2015-01 18:48:05.123');
//$ts->format('U = Y-m-d H:i:s');
$params["timestamp"] = $ts->format('Y-m-d H:i:s');
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function: ".$fun."\n Parameters: ",$params, "\nSuccess: ".$result["success"].". Message: ".$result["message"]);
*/

//get user favourites
/*$fun="getUserFavPosts";
$params = array();
$params["user_id"] = 1;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Success: ".$result["success"].". Message: ".$result["message"]);
ChromePhp::log($result["posts"]);*/

/*//addPost
$fun = "addPost";
$params = array();
$params["poster_id"] = 3;
$params["content"] = "I am a post. Post, post, post.";
$ts = new DateTime('2015-01 18:48:05.123');
//$ts->format('U = Y-m-d H:i:s');
$params["timestamp"] = $ts->format('Y-m-d H:i:s');
$params["favourite_counter"] = 0;
ChromePhp::log($params);

$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log($result["success"]);
ChromePhp::log($result["message"]);*/


/*//get home posts
$fun="getHomePosts";
$params = array();
$params["user_id"] = 3;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$fun."\nParameters used:\n",$params,"\nSuccess: ".$result["success"].". Message: ".$result["message"]);
ChromePhp::log("Home posts\n",$result["home_posts"]);*/

/*//get user's posts
$fun = "getUserPosts";
$params = array();
$params["user_id"] = 1;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$fun."\nParameters used:\n",$params,"\nSuccess: ".$result["success"].". Message: ".$result["message"]);
if($result["success"] == 1) {
	ChromePhp::log("User's posts:\n",$result["posts"]);
}*/


/*//get user friends
$fun = "getUserFriends";
$params = array();
$params["user_id"] = 1;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$fun.".\nParameters used: ",$params,"\nSuccess: ".$result["success"].". \nMessage: ". $result["message"]);
if($result["success"] == 1){
	ChromePhp::log("Friends:\n",$result["friends"]);	
}*/

/*//get user pending friends
$fun = "getUserPendingFriends";
$params = array();
$params["user_id"] = 1;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$fun.".\nParameters used: ",$params,"\nSuccess: ".$result["success"].". \nMessage: ". $result["message"]);
if($result["success"] == 1){
	ChromePhp::log("Pending friends:\n",$result["pending_friends"]);	
}*/



/*//register
//parameters
$params = array();
$params["username"] = "new_user_4";
$params["email"] = "new_email4";
$params["password"] = "new_user_1_password";
ChromePhp::log("parameters used:\n",$params);
$fun = "register";
$result = json_decode($db_handler->select_function($fun,$params),true);

ChromePhp::log("Function used: ".$fun.".\nParameters used: ",$params,"\nSuccess: ".$result["success"].". \nMessage: ". $result["message"]);*/

/*//login
//parameters
$params = array();
$params["username"] = "user2";
$params["password"] = "password2";
ChromePhp::log("parameters used:\n",$params);
$fun = "login";
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("result:\n","Success: ". $result["success"].". Message: ".$result["message"]);
if($result["success"]) {
	ChromePhp::log("User:\n",$result["user"]);
}*/


/*
$data = array();
$data["username"] = "userrrrr";
$data["password"] = "password2";
ChromePhp::log("data sent:\n",$data);
$fun = "login";
$params = $data;
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$data["fun"]."\nParameters used: ",$data,"\nSuccess: ". $result["success"].". Message: ".$result["message"]);
ChromePhp::log("Result:", $result);
echo json_encode($result);
*/
/*
audio detect
$params = array();
$fun = "detectAudio";
$params = array();
$result = json_decode($db_handler->select_function($fun,$params),true);
ChromePhp::log("Function used: ".$data["fun"]."\nParameters used: ",$data,"\nSuccess: ". $result["success"].". Message: ".$result["message"]);
	ChromePhp::log("Result:", $result);
	echo json_encode($result);*/


#$song_name = passthru("dip_detect_recorded_files.py "."1518987260883.3gp");
#echo passthru("dip_detect_recorded_files.py "."1518987260883.3gp");

//exec("dip_detect_recorded_files.py "."1518987260883.3gp");
#echo "result:".$result;


/*
$file = "1519056596217.3gp";
$script_name = "\"C:\wamp64\www\ada_login_api\Source_Files\dip_detect_recorded_files.py\"";
$redirect_err = "2>&1";
$script_output = null;
$script_result = -4;
$rez = exec($python_path. ' '.$script_name.' '.$file.' 2>&1',$script_output,$script_result);

$response = array();
$song_name = "";
if($rez) {
	#exec je uspela. Še preverimo naš ukaz
	if($script_result == 0) {
		#uspeh!
		$song_name = $script_output[0];
		$response["success"] = 1;
		$response["message"] = "Song detection successful.";
		$response["song_name"] = $script_output[0];
	} else {
		$response["success"] = 0;
		$response["message"] = "Song detection unsuccessful. Exec ok";
		$response["song_name"] = "unknown";
		$song_name = "unknown";
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Song detection unsuccessful.";
	$response["song_name"] = "unknown";
	$song_name = "unknown";
}
if($rez) {
	$str = "";
	foreach($script_output as $item) {
	    $str = $str ."</br>". $item;
	}
	echo $str;
	#echo implode(',',$out);
	#echo "rez is true. out: ".$out[0]." size ".count($out);
} else {
	echo "wasd";
}
echo "success: ".$response["success"]."</br>"."message: ".$response["message"]."</br>"."song name: ".$response["song_name"]."</br>".$str;

*/
/*

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	$content = $data["fileContent"];#$_POST["fileContent"];
	$contentDecoded = base64_decode($content);
	$file_name = $data["filename"].".3gp";#$_POST["filename"].".3gp";#.$_POST["fileType"];
	file_put_contents($file_name, $contentDecoded);


	#exec rab met ('ukaz') znotrej pa rabs poti v ""
	$python_path = "\"C:\Program Files (x86)\Python27\python\"";
	#$file_name = $POST_["filename"];#"1519014611183.3gp";#"1518987260883.3gp";
	
	#$script_name = "\"C:\wamp64\www\ada_login_api\Source_Files\\resources\dejavu\dip_detect_recorded_files.py\"";
	$script_name = "\"C:\wamp64\www\ada_login_api\Source_Files\dip_detect_recorded_files.py\"";
	$redirect_err = "2>&1";
	$script_output = null;
	$script_result = -4;
	$rez = exec($python_path. ' '.$script_name.' '.$file_name.' 2>&1',$script_output,$script_result);
	file_put_contents("somefile.txt",$script_output);



	$response = array();
	$song_name = "";
	if($rez) {
		#exec je uspela. Še preverimo naš ukaz
		if($script_result == 0) {
			#uspeh!
			$song_name = $script_output[0];
			$response["success"] = 1;
			$response["message"] = "Song detection successful.";
			$response["song_name"] = $script_output[0];
		} else {
			$response["success"] = 0;
			$response["message"] = "Song detection unsuccessful.";
			$response["song_name"] = "unknown";
			$song_name = "unknown";
		}
	} else {
		$response["success"] = 0;
		$response["message"] = "Song detection unsuccessful.";
		$response["song_name"] = "unknown";
		$song_name = "unknown";
	}
	echo json_encode($response);# $song_name;
}*/

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	ChromePhp::log("data sent:\n",$data);
	$fun = $data["fun"];
	$params = $data;
	$result = json_decode($db_handler->select_function($fun,$params),true);
	ChromePhp::log("Function used: ".$data["fun"]."\nParameters used: ",$data,"\nSuccess: ". $result["success"].". Message: ".$result["message"]);
	ChromePhp::log("Result:", $result);
	echo json_encode($result);
}
?>