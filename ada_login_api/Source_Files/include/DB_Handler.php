<?php 
//funkcije imam posebej v 2 razredih - User in Post.
//tale razred se bo povezal na bazo, poklicu tapravo funkcijo in vrnu rezultat
ChromePhp::log("page: DB_Handler.php");
class DB_Handler {
	private $conn;
	private $db;
	function __construct() {
		require_once 'DB_Connect.php';
		$this->db = new DB_Connect();
		$this->conn = $this->db->connect();
	}
	//destruktor
	function __destruct() {
		if($this->conn != null) {
			$this->conn = $this->db->close();	
		}
	}
	public function select_function($fun, $parameters) {
		ChromePhp::log("Function selected: ".$fun);
		$wrong_param = false;
		switch($fun) {
			case "login":
				if(isset($parameters["username"]) && isset($parameters["password"])) {
					$uf = new User_Functions();
					return ($uf->login($parameters,$this->conn));
				} else {
					$wrong_param = true;
				}
				break;
			case "register":
				if(isset($parameters["username"]) && isset($parameters["email"]) && isset($parameters["password"])) {
					$uf = new User_Functions();
					return $uf->register($parameters,$this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "getUserFriends":		
				if(isset($parameters["user_id"])) {
					$uf = new User_Functions();
					return $uf->get_user_friends($parameters,$this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "getUserPendingFriends":
				if(isset($parameters["user_id"])) {
					$uf = new User_Functions();
					return $uf->get_user_pending_friends($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "getHomePosts":
				if(isset($parameters["user_id"])) {
					$uf = new User_Functions();
					return $uf->get_home_posts($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "addPost":
				if(isset($parameters["poster_id"]) && isset($parameters["content"]) && isset($parameters["timestamp"]) && isset($parameters["favourite_counter"]))
				{
					$uf = new User_Functions();
					return $uf->add_post($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "getUserPosts":
				if($parameters["user_id"]) {
					$uf = new User_Functions();
					return $uf->get_user_posts($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "getUserFavPosts":
				if($parameters["user_id"]) {
					$uf = new User_Functions();
					return $uf->get_user_fav_posts($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "addPostToFavourites":
				if(isset($parameters["user_id"]) && isset($parameters["post_id"]) && isset($parameters["timestamp"])) {
					$uf = new User_Functions();
					return $uf->add_post_to_favourites($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "toggleFavouritePost":
				if(isset($parameters["user_id"]) && isset($parameters["post_id"]) && isset($parameters["timestamp"])) {
					$uf = new User_Functions();
					return $uf->toggle_favourite_post($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "removeFromFavourites":
				if(isset($parameters["user_id"]) && isset($parameters["post_id"])) {
					$uf = new User_Functions();
					return $uf->delete_post_from_favourites($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "sendFriendRequest":
				if(isset($parameters["sender_id"]) && isset($parameters["receiver_username"])) {
					$uf = new User_Functions();
					return $uf->send_friend_request($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "acceptFriendRequest":
				if(isset($parameters["user_id"]) && isset($parameters["friend_id"]) && isset($parameters["friend_username"])) {
					$uf = new User_Functions();
					return $uf->accept_friend_request($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "declineFriendRequest":
				if(isset($parameters["user_id"]) && isset($parameters["friend_id"]) && isset($parameters["friend_username"])) {
					$uf = new User_Functions();
					return $uf->decline_friend_request($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "deleteFriend":
				if(isset($parameters["user_id"]) && isset($parameters["friend_id"]) && isset($parameters["friend_username"])) {
					$uf = new User_Functions();
					return $uf->delete_friend($parameters, $this->conn);
				} else {
					$wrong_param = true;
				}
				break;
			case "updateEmail":
				break;
			case "updateName":
				break;
			case "updateDescription":
				break;
		}
		if($wrong_param) {
			$response = array();
			$response["success"] = 0;
			$response["message"] = "Wrong parameters.";
		}
	}
}
?>