<?php
include ('resources/chromephp/ChromePhp.php');
ChromePhp::log('page: index.php');

require_once('include/User_Functions.php');
require_once('include/DB_Handler.php');


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
}
*/


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