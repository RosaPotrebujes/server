<?php
ChromePhp::log('page: User_Functions.php');

class User_Functions {
	private $conn;

	//konstruktor
	function __construct() {
		//require_once 'DB_Connect.php';
	//	$this->conn = new DB_Connect();
	}
	//destruktor
	function __destruct() {

	}
	public function login($params,$conn) {
		$response = array();
		$user_r = $this->get_user_by_name_and_pass($params['username'], $params['password'], $conn);
		$user_r = json_decode($user_r, true);
		ChromePhp::log($user_r);	
		if((!$user_r["user"]) && $user_r["success"] == 1) {
			ChromePhp::log("user:sss");
			$response["success"] = 0;
			$response["message"] = "Wrong login credentials.";
		} else if ($user_r["user"] && $user_r["success"] == 1) {
			$response["success"] = 1;
			$response["message"] = "Login successful.";
			$response["user"] = $user_r["user"];
		} else {
			$response = $user_r;
		}
		return json_encode($response);
	}
	public function register($params,$conn) {
		$response = array();
		$message = "";
		$nameTaken = $this->check_if_name_taken($params["username"],$conn);
		$emailTaken = $this->check_if_email_taken($params["email"],$conn);
		if($nameTaken){
			$message = "Username ";
		}
		if($emailTaken) {
			if($nameTaken){
				$message = $message . "and email ";
			} else {
				$message = "Email ";
			}
		}
		if($emailTaken || $nameTaken) {
			$message = $message . "already taken.";
			$response["success"] = 0;
			$response["message"] = $message;
			return json_encode($response);
		} else {
			$insert_r = json_decode($this->insert_user($params,$conn),true);
			if($insert_r["success"] == 1) {
				$response["success"] = 1;
				$response["message"] = "User registered.";
			} else {
				$response["success"] = 0;
				ChromePhp::log("User insert failed:\nparams:",$params,"\nSuccess: ".$insert_r["success"].". Message:".$insert_r["message"]);
				$response["message"] = "Registration failed.";
			}
			return json_encode($response);
		}
	}

	public function get_user_by_name_and_pass($name,$pass,$conn) {
		$response = array();
		try {
			$stmt = $conn->prepare('SELECT user.* FROM user, login WHERE  user.user_id = (SELECT user_id FROM user WHERE username = :username) AND login.user_id = user.user_id and login.password =:password');
			$stmt->bindValue(':username',$name);
			$stmt->bindValue(':password',$pass);
			$stmt->execute();
			if($stmt->execute()) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				if($result) {
					$response["success"] = 1;
					$response["message"] = "Found user with given username and password.";
					$response["user"] = $result;
				} else {
					$response["success"] = 1;
					$response["message"] = "No user with given credentials found.";
					$response["user"] = $result;
				}
			} else {
				$response["success"] = 0;
				$response["message"] = "Could not find user with give username and password.";
			}
			return json_encode($response);
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
			//echo $e->getMessage();
		}
	}
	public function check_if_name_taken($name,$conn) {
		try {
			$stmt = $conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$stmt->bindValue(':username',$name);
			$stmt->execute();
			if($stmt->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}
	}
	public function check_if_email_taken($email,$conn) {
		try {
			$smtm = $conn->prepare('SELECT user_id FROM user WHERE email = :email LIMIT 1');
			$smtm->bindValue(':email',$email);
			$smtm->execute();
			if($smtm->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}
	}

	public function insert_user($params,$conn) {
		$response = array();
		try {
			$stmt = $conn->prepare('INSERT INTO user (username, email, description) VALUES (:username, :email, :description)');
			$stmt->bindValue(':username',$params["username"]);
			$stmt->bindValue(':email',$params["email"]);
			$stmt->bindValue(':description',"");
			$insertedUser = $stmt->execute();
			if($insertedUser) {
				//get the id
				$user_id = $conn->lastInsertId();
				try {
					$stmt = $conn->prepare('INSERT INTO login (user_id, password) VALUES (:user_id, :password)');
					$stmt->bindValue(':user_id', $user_id);
					$stmt->bindValue('password', $params["password"]);
					$insertedLogin = $stmt->execute();
					if($insertedLogin) {
						$response["success"] = 1;
						$response["message"] = "Login insert sucessful.";
					} else {
						$response["success"] = 0;
						$response["message"] = "Login insert unsuccessful.";
					}
					return json_encode($response);
				} catch (PDOException $e) {
					ChromePhp::log($e->getMessage());//echo $e->getMessage();
					//pobrišemo kar smo dodal v user tabelo:
					$this->delete_user($user_id,$conn);
					$response["success"] = 0;
					$response["message"] = $e->getMessage();
					return json_encode($response);
				}
			} else {
				$response["success"] = 0;
				$response["message"] = "User insert failed.";
				return json_encode($response);
			}
			
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}

	public function delete_user($user_id,$conn) {
		try {
			$stmt = $conn->prepare('DELETE FROM user WHERE user_id=:user_id');
			$stmt->bindValue(':user_id', $user_id);
			$stmt->execute();
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
		}
	}

	public function get_user_friends($params, $conn) {
		$response = array();
		$exists = $this->check_if_user_exists_by_id($params["user_id"],$conn);
		if(!$exists) {
			$response["success"] = 0;
			$response["message"] = "User with given id does not exist.";
			return json_encode($response);
		}
		try {
			$stmt = $conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=1');
			$stmt->bindValue(':user_id',$params["user_id"]);
			if($stmt->execute()) {
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if($stmt->rowCount() == 0) {
					$response["success"] = 1;
					$response["message"] = "User has no friends.";
					$response["friends"] = array();
					return json_encode($response);
				}

				// tabelo v IN() rabm spremenit v string
				$friend_ids = "";
				for ($i = 0; $i < sizeof($result); $i++) {
					$row = $result[$i];
					//id prijatelja
					$f_id = ($row["user_one_id"] == $params["user_id"]) ? $row["user_two_id"] : $row["user_one_id"];
					$friend_ids = $friend_ids . $f_id;
					if($i != sizeof($result)-1) {
						$friend_ids = $friend_ids . ",";
					}
				}
				//ChromePhp::log("string ids: ".$friend_ids);
				//dobim userje:
				try {
					$stmt = $conn->prepare('SELECT * FROM user WHERE user_id IN ('.$friend_ids.')');
					//$stmt->bindValue(':friends', $friend_ids);
					$stmt->execute();
					$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$response["success"] = 1;
					$response["message"] = "Got user's friends.";
					$response["friends"] = $friends;
					return json_encode($response);
						
				} catch (PDOException $e) {
					ChromePhp::log($e->getMessage());//echo $e->getMessage();
					$response["success"] = 0;
					$response["message"] = $e->getMessage();
					return json_encode($response);
				}
			} else {
				$response["success"] = 0;
				$response["message"] = "Could not get friends.";
				return json_encode($response);
			}
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}
	public function get_user_pending_friends($params, $conn) {
		$response = array();
		$exists = $this->check_if_user_exists_by_id($params["user_id"],$conn);
		if(!$exists) {
			$response["success"] = 0;
			$response["message"] = "User with given id does not exist.";
			return json_encode($response);
		}
		try {
			$stmt = $conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=0 AND action_user_id=:user_id');
			$stmt->bindValue(':user_id',$params["user_id"]);
			if($stmt->execute()) {
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

				if($stmt->rowCount() == 0) {
					$response["success"] = 1;
					$response["message"] = "User has no pending friendships.";
					$response["pending_friends"] = array();
					return json_encode($response);
				}

				// tabelo v IN() rabm spremenit v string
				$friend_ids = "";
				for ($i = 0; $i < sizeof($result); $i++) {
					$row = $result[$i];
					//id prijatelja
					$f_id = ($row["user_one_id"] == $params["user_id"]) ? $row["user_two_id"] : $row["user_one_id"];
					$friend_ids = $friend_ids . $f_id;
					if($i != sizeof($result)-1) {
						$friend_ids = $friend_ids . ",";
					}
				}
				//ChromePhp::log("string ids: ".$friend_ids);
				//dobim userje:
				try {
					$stmt = $conn->prepare('SELECT * FROM user WHERE user_id IN ('.$friend_ids.')');
					//$stmt->bindValue(':friends', $friend_ids);
					$stmt->execute();
					$pending_friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$response["success"] = 1;
					$response["message"] = "Got user's pending friendships.";
					$response["pending_friends"] = $pending_friends;
					return json_encode($response);
						
				} catch (PDOException $e) {
					ChromePhp::log($e->getMessage());//echo $e->getMessage();
					$response["success"] = 0;
					$response["message"] = $e->getMessage();
					return json_encode($response);
				}
			} else {
				$response["success"] = 0;
				$response["message"] = "Could not get pending friendships.";
				return json_encode($response);
			}
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}

	public function get_home_posts($params, $conn) {
		$response = array();
		$home_posts = array();
		$user_id = $params["user_id"];
		$exists = $this->check_if_user_exists_by_id($user_id,$conn);
		if(!$exists) {
			$response["success"] = 0;
			$response["message"] = "User with given id does not exist.";
			return json_encode($response);
		}
		try {
			//dobim idje prijateljev -> ze v stringu
			$friends_ids_r = json_decode($this->get_user_friends_ids($user_id, $conn),true);
			if($friends_ids_r["success"] == 0) {
				$response["success"] = 0;
				$response["message"] = "Home posts could not be retreived.";
				return json_encode($response);
			}
			if($friends_ids_r["friends_ids"] == "") {
				$response["success"] = 1;
				$response["message"] = "Home posts retreived successfully.";
				$response["home_posts"] = array();
				return json_encode($response);
			}
			$stmt = $conn->prepare('SELECT post.*,user.username FROM post,user WHERE post.poster_id IN ('.$friends_ids_r["friends_ids"].') AND post.poster_id=user.user_id ORDER BY timestamp DESC');			
			if($stmt->execute()){
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$response["success"] = 1;
				$response["message"] = "Home posts retreived successfully.";
				$response["home_posts"] = $result;
			} else {
				$response["success"] = 0;
				$response["message"] =	"Home posts could not be retreived.";
			}
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
		}
	}

	public function get_user_posts($params, $conn) {
		$response = array();
		try {
			$user_id = $params["user_id"];
			$exists = $this->check_if_user_exists_by_id($user_id,$conn);
			if(!$exists) {
				$response["success"] = 0;
				$response["message"] = "User with given id does not exist.";
				return json_encode($response);
			}
			$stmt = $conn->prepare('SELECT post.*,user.username FROM post,user WHERE post.poster_id=:user_id AND user.user_id=post.poster_id');
			$stmt->bindValue(':user_id',$user_id);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Posts retreived successfully.";
				$response["posts"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return json_encode($response);
			} else {
				$response["success"] = 0;
				$response["message"] = "Posts could not be retreived.";
				return json_encode($response);	
			}
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}	
	}

	public function get_user_friends_ids($user_id,$conn) {
		$response = array();
		$exists = $this->check_if_user_exists_by_id($user_id,$conn);
		if(!$exists) {
			$response["success"] = 0;
			$response["message"] = "User with given id does not exist.";
			return json_encode($response);
		}
		try {
			$stmt = $conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=1');
			$stmt->bindValue(':user_id',$user_id);
			if($stmt->execute()) {
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// tabelo v IN() rabm spremenit v string
				$friend_ids = "";
				for ($i = 0; $i < sizeof($result); $i++) {
					$row = $result[$i];
					//id prijatelja
					$f_id = ($row["user_one_id"] == $user_id) ? $row["user_two_id"] : $row["user_one_id"];
					$friend_ids = $friend_ids . $f_id;
					if($i != sizeof($result)-1) {
						$friend_ids = $friend_ids . ",";
					}
				}
				$response["success"] = 1;
				$response["message"] = "User's friends' ID retreived.";
				$response["friends_ids"] = $friend_ids;
			} else {
				$response["success"] = 0;
				$response["message"] = "User's friends' ID could not be retreived.";
			}
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}	
	}

	public function get_user_by_id($user_id,$conn) {
		try {
			$response = array();
			$stmt = $conn->prepare('SELECT * FROM user WHERE user_id=:user_id LIMIT 1');
			$stmt->bindValue(':user_id',$user_id);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				$response["success"] = 1;
				$response["message"] = "User with id ".$user_id." found";
				$response["user"] = $result;
			} else {
				$response["success"] = 0;
				$response["message"] = "User with id ".$user_id." not found";
			}
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}

	public function add_post($params, $conn) {
		$response = array();
		try{
			$stmt = $conn->prepare('INSERT INTO post (poster_id, content, timestamp, favourite_counter) VALUES (:poster_id, :content, :timestamp, :favourite_counter)');
			$stmt->bindValue(':poster_id',$params["poster_id"]);
			$stmt->bindValue(':content',$params["content"]);
			$stmt->bindValue(':timestamp',$params["timestamp"]);
			$stmt->bindValue('favourite_counter', $params["favourite_counter"]);
			$inserted = $stmt->execute();
			if($inserted) {
				$response["success"] = 1;
				$response["message"] = "New post created.";
			/*	$post_id = $conn->lastInsertId();
				$stmt = $conn->prepare('SELECT * FROM post WHERE post_id=:post_id');
				$stmt->bindValue(':post_id',$post_id);
				$stmt->execute();
				ChromePhp::log($stmt->fetch(PDO::FETCH_ASSOC));	*/

			} else {
				$response["success"] = 0;
				$response["message"] = "New post creation unsuccessful.";
			}
			ChromePhp::log($response);
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}
	public function get_user_fav_posts($params,$conn) {
		//za usak slučaj lahko dodam check če user obstaja
		$response = array();
		$user_r = json_decode($this->get_user_by_id($params["user_id"],$conn),true);
		if ($user_r["success"] == 0) {
			$response["success"] = 0;
			$response["message"] = "User with given ID does not exist.";
			return json_encode($response);
		}
		try{
			$stmt = $conn->prepare('SELECT post_id FROM favourite WHERE user_id=:user_id');
			$stmt->bindValue(':user_id',$params["user_id"]);
			if($stmt->execute()) {
				//dobim idje od fav postov
				$post_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$s_ids = ""; //rabm string za IN()
				for($i=0;$i<sizeof($post_ids);$i++) {
					$s_ids = $s_ids . $post_ids[$i]["post_id"];
					if($i != sizeof($post_ids)-1){
						$s_ids = $s_ids . ",";
					}
				}
				//preverm ce sploh ma kere poste pod fav.
				if($s_ids == "") {
					$response["success"] = 1;
					$response["message"] = "User has no favourite posts";
					return json_encode($response);
				}
				//dobim poste
				$posts_by_ids_response = json_decode($this->get_posts_by_ids($s_ids,$conn),true);
				//pogledam če smo uspel dobit posts, če ne kar vrnem message
				if($posts_by_ids_response["success"] == 1) {
					$response["success"] = 1;
					$response["message"] = "Got user's favourite posts successfully.";
					$response["posts"] = $posts_by_ids_response["posts"];
				} else {
					$response = $posts_by_ids_response;
				}
			} else {
				$response["success"] = 0;
				$response["message"] = "Something went wrong. Could not get user's favourite posts.";
			}
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
			//echo $e->getMessage();
		}
		
	}

	public function get_posts_by_ids($post_ids,$conn) {
		$response = array();
		try{
			$stmt = $conn->prepare('SELECT * FROM post WHERE post_id IN ('. $post_ids .')');
			//ChromePhp::log($stmt);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Found posts by their ids successfully.";
				$response["posts"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$response["success"] = 0;
				$response["message"] = "Could not get posts by their ids.";
			}
			return json_encode($response);
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
			//echo $e->getMessage();
		}
	}
	public function check_if_user_exists_by_id($user_id,$conn) {
		try {
			$stmt = $conn->prepare('SELECT user_id FROM user WHERE user_id = :user_id LIMIT 1');
			$stmt->bindValue(':user_id',$user_id);
			$stmt->execute();
			if($stmt->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}	
	}

	public function toggle_favourite_post($params, $conn) {
		$response = array();
		$is_fav = $this->is_users_fav($params["user_id"], $params["post_id"], $conn);
		if($is_fav) {
			$d_r = json_decode($this->delete_post_from_favourites($params,$conn),true);
			if($d_r["success"] == 1) {
				$response["success"] = 1;
				$response["message"] = "Post was deleted from favourites.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Post could not be deleted from favourites.";
			}
		} else {
			$i_r = json_decode($this->add_post_to_favourites($params, $conn),true);
			if($i_r["success"] == 1) {
				$response["success"] = 1;
				$response["message"] = "Post was added to favourites.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Post could not be added to favourites.";
			}
		}
		return json_encode($response);
	}

	public function add_post_to_favourites($params, $conn) {
		$response = array();
		try {
			$stmt = $conn->prepare('INSERT into favourite (user_id, post_id, timestamp) VALUES (:user_id, :post_id, :timestamp)');
			$stmt->bindValue(':user_id',$params["user_id"]);
			$stmt->bindValue(':post_id',$params["post_id"]);
			$stmt->bindValue(':timestamp',$params["timestamp"]);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Post added to favourites.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Post could not be added to favourites.";
			}
			return json_encode($response);
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}

	public function delete_post_from_favourites($params, $conn) {
		$response = array();
		$user_id = $params["user_id"];
		$post_id = $params["post_id"];
		try {
			$stmt = $conn->prepare('DELETE from favourite WHERE user_id=:user_id AND post_id=:post_id');
			$stmt->bindValue(':user_id',$user_id);
			$stmt->bindValue(':post_id',$post_id);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Post removed from favourites.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Could not delete post from favourites.";
			}	
			return json_encode($response);
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
			return json_encode($response);
		}
	}

	public function is_users_fav($user_id, $post_id, $conn) {
		try {
			$stmt = $conn->prepare('SELECT post_id FROM favourite WHERE post_id=:post_id AND user_id=:user_id');
			$stmt->bindValue(':post_id',$post_id);
			$stmt->bindValue(':user_id',$user_id);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}
	}

	public function get_user_id_by_name($username, $conn) {
		try {
			$stmt = $conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$stmt->bindValue(':username',$username);
			$stmt->execute();
			$result = -1;
			if($stmt->rowCount()>0) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$result = $result["user_id"];
			}
			return $result;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			return -1;
		}
	}

	public function send_friend_request($params, $conn) {
		$response = array();
		$receiver_id = $this->get_user_id_by_name($params["receiver_username"],$conn);
		$sender_id = $params["sender_id"];
		if($receiver_id < 0) {
			$response["success"] = 0;
			$response["message"] = "User ".$params["receiver_username"]." not found.";
		} else {
			$user_one_id = min($sender_id,$receiver_id);
			$user_two_id = max($sender_id,$receiver_id);
						
			try {
				$stmt = $conn->prepare('SELECT status, action_user_id FROM friend WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id LIMIT 1');
				$stmt->bindValue(':user_one_id',$user_one_id);
				$stmt->bindValue(':user_two_id',$user_two_id);
				$stmt->execute();

				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				if($result['status'] == 1) {
					$response["success"] = 0;
					$response["message"] = $params["receiver_username"]." is already your friend.";
				} else if ($result['action_user_id'] == $sender_id && $result['status'] == 0) {
					$response["success"] = 0;
					$response["message"] = "Friend request already sent.";
				} else if ($result["action_user_id"] == $receiver_id && $result["status"] == 0) {
					try {
						ChromePhp::log("user one id ", $user_one_id, " lalala user 2 id ", $user_two_id);
						$stmt = $conn->prepare('UPDATE friend SET status=:new_status WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id AND status=0 LIMIT 1');
						$stmt->bindValue(':user_one_id',$user_one_id);
						$stmt->bindValue(':user_two_id',$user_two_id);
						$stmt->bindValue(':new_status',1);
						if($stmt->execute()) {
							$response["success"] = 1;
							$response["message"] = "Friendship with ".$params["receiver_username"]." confirmed.";
						} else {
							$response["success"] = 0;
							$response["message"] = "Friendship could not be accepted.";
						}
					} catch(PDOException $e) {
						ChromePhp::log($e->getMessage());
						//echo $e->getMessage();
						$response["success"] = 0;
						$response["message"] = $e->getMessage();
					}
				} else {
					try {
						$stmt = $conn->prepare('INSERT into friend (user_one_id, user_two_id, status, action_user_id) VALUES (:user_one_id, :user_two_id, :status, :action_user_id)');
						$stmt->bindValue(':user_one_id',$user_one_id);
						$stmt->bindValue(':user_two_id',$user_two_id);
						$stmt->bindValue(':status',0);
						$stmt->bindValue('action_user_id',$sender_id);
						if($stmt->execute()) {
							$response["success"] = 1;
							$response["message"] = "Friend request sent.";
						} else {
							$response["success"] = 0;
							$response["message"] = "Could not send friend request.";
						}
					} catch(PDOException $e) {
						ChromePhp::log($e->getMessage());
						//echo $e->getMessage();
						$response["success"] = 0;
						$response["message"] = $e->getMessage();
					}	
				}
			} catch(PDOException $e) {
				ChromePhp::log($e->getMessage());
				//echo $e->getMessage();
				$response["success"] = 0;
				$response["message"] = $e->getMessage();
			}
		}
		return json_encode($response);
	}

	public function accept_friend_request($params, $conn) {
		$response = array();
		$user_id = $params["user_id"];
		$friend_id=$params["friend_id"];
		try {
			$user_one_id = min($user_id, $friend_id);
			$user_two_id = max($user_id, $friend_id);

			$stmt = $conn->prepare('UPDATE friend SET status=:new_status WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id AND status=0 LIMIT 1');
			$stmt->bindValue(':user_one_id',$user_one_id);
			$stmt->bindValue(':user_two_id',$user_two_id);
			$stmt->bindValue(':new_status',1);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Friendship with ".$params["friend_username"]." confirmed.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Friendship with ".$params["friend_username"]." could not be confirmed.";
			}
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
		}
		return json_encode($response);
	}

	public function decline_friend_request($params, $conn) {
		$response = array();
		$user_id = $params["user_id"];
		$friend_id=$params["friend_id"];
		try {
			$user_one_id = min($user_id, $friend_id);
			$user_two_id = max($user_id, $friend_id);
			$stmt = $conn->prepare('DELETE FROM friend WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id AND status=0 LIMIT 1');
			$stmt->bindValue(':user_one_id',$user_one_id);
			$stmt->bindValue(':user_two_id',$user_two_id);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Friendship with ".$params["friend_username"]." declined.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Friendship with ".$params["friend_username"]." could not be declined.";
			}
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
		}
		return json_encode($response);
	}

	public function delete_friend($params, $conn) {
		$response = array();
		$user_id = $params["user_id"];
		$friend_id=$params["friend_id"];
		try {
			$user_one_id = min($user_id, $friend_id);
			$user_two_id = max($user_id, $friend_id);
			$stmt = $conn->prepare('DELETE FROM friend WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id AND status=1 LIMIT 1');
			$stmt->bindValue(':user_one_id',$user_one_id);
			$stmt->bindValue(':user_two_id',$user_two_id);
			if($stmt->execute()) {
				$response["success"] = 1;
				$response["message"] = "Friend ".$params["friend_username"]." deleted.";
			} else {
				$response["success"] = 0;
				$response["message"] = "Friend ".$params["friend_username"]." could not be deleted.";
			}
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
			$response["success"] = 0;
			$response["message"] = $e->getMessage();
		}
		return json_encode($response);
	}
/*
	public function insertUser($user) {
		try {
			//$db = new DB_Connect();
			$smtm = $this->$conn->prepare('INSERT INTO user (username, email, description) VALUES (:username, :email, :description)');
			$smtm->bindValue(':username',$name);
			$smtm->bindValue(':email',$email);
			$smtm->bindValue(':description',"");
			$smtm->execute();
			//get the id
			$user_id = $db->lastInsertId();

			$smtm = $this->conn->prepare('INSERT INTO login (user_id, password) VALUES (:user_id, :password)');
			$smtm->bindValue(':user_id', $user_id);
			$smtm->bindValue('password', $password);
			$smtm->execute();
			
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function deleteUser($user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('DELETE FROM user WHERE user_id=:user_id');
			$smtm->bindValue(':user_id', $user_id);
			$smtm->execute();
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}

	}
	public function getUserById($user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT * FROM user WHERE user_id = :userId LIMIT 1');
			$smtm->bindValue(':user_id', $user_id);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result;
			}
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function getUserByName($name) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT * FROM user WHERE username=:username LIMIT 1');
			$smtm->bindValue(':username',$name);
			$smtm->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result;
			}
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function getNameById($user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT username FROM user WHERE user_id=:user_id LIMIT 1');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result['username'];
			}
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function getUserIdByName($name,$conn) {
		try {
			//$db = new DB_Connect();
			$stmt = $conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$stmt->bindValue(':username',$name);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result['user_id'];
			} else {
				return -1;
			}
		} catch (PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}
	}
	public function getUserByNameAndPass($name,$pass,$conn) {
		ChromePhp::log("Using function: getUserByNameAndPass");
		try {
			//check username
			$user_id = $this->getUserIdByName($name,$conn);
			
			$stmt = $conn->prepare('SELECT user.* FROM user, login WHERE  user.user_id = (SELECT user_id FROM user WHERE username = :username) AND login.user_id = user.user_id and login.password =:password');
			
			$stmt->bindValue(':username',$name);
			$stmt->bindValue(':password',$pass);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
			return $result;
		} catch(PDOException $e) {
			ChromePhp::log($e->getMessage());
			//echo $e->getMessage();
		}
	}
	public function updateUserDescription($user_id, $new_description) {
		try {
			//$db = new DB_Connect();
			$smtm = $this->$conn->prepare('UPDATE user SET description=:new_description WHERE user_id=:user_id');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->bindValue(':new_description',$new_description);
			$smtm->execute();
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function checkIfNameTaken($name) {
		try {
			//poglej, ce je username ze v bazi
			//$db = new DB_Connect();
			$stmt = $this->$conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$stmt->bindValue(':username',$name);
			$smtm->execute();
			if($smtm->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function checkIfEmailTaken($email) {
		try {
			//$db = new DB_Connect();
			$smtm = $this->$conn->prepare('SELECT user_id FROM user WHERE email = :email LIMIT 1');
			$smtm->bindValue(':email',$email);
			$smtm->execute();
			if($smtm->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function updateName($user_id, $new_name) {
		try {
			//$db = new DB_Connect();
			$smtm = $this->$conn->prepare('UPDATE user SET username=:new_username WHERE user_id=:user_id');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->bindValue(':new_username',$new_name);
			$smtm->execute();
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}	

	}
	public function updateEmail($user_id, $new_email) {
		try {
			//$db = new DB_Connect();
			$smtm = $this->$conn->prepare('UPDATE user SET email=:newEmail WHERE user_id=:user_id');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->bindValue(':new_email',$new_email);
			$smtm->execute();
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function getUserFriends($user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=1');
			$smtm->bindValue(':user_id',$userID);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			$friends = [];
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					if($row[user_one_id] == $user_id) {
						$friends[] = $row[user_two_id];
					} else {
						$friends[] = $row[user_one_id];
					}
				}
			}
			$smtm = $this->$conn->prepare('SELECT * FROM user WHERE user_id IN (:friends)');
			$smtm->bindValue(':friends', $friends);
			$smtm->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);

			return $result;
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function getPendingFriendships($user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=0');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			$friends = [];
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					if($row[user_one_id] == $user_id) {
						$friends[] = $row[user_two_id];
					} else {
						$friends[] = $row[user_one_id];
					}
				}
			}
			$smtm = $this->$conn->prepare('SELECT * FROM user WHERE user_id IN (:friends)');
			$smtm->bindValue(':friends', $friends);
			$smtm->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);

			return $result;
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function acceptFriendship($user_one_id, $user_two_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('UPDATE friend SET status=:1 WHERE user_one_id=:user_one_id AND user_two_id=:user_two_id');
			$smtm->bindValue(':user_one_id',$user_one_id);
			$smtm->bindValue(':user_two_id',$user_two_id);
			$smtm->execute();			
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function sendFriendshipRequest($user_id_one, $user_id_two, $action_user_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE (user_one_id=:user_id OR user_two_id=:user_id) AND status=0');
			$smtm->bindValue(':user_id',$userID);
			$smtm->execute();			
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function deleteFriend($user_one_id, $user_two_id) {
		try {
			//$db = new DB_Connect();

			$smtm = $this->$conn->prepare('DELETE FROM friend WHERE (user_one_id=:userOneId AND user_two_id=:userTwoId AND status=1)');
			$smtm->bindValue(':user_one_id',$user_one_id);
			$smtm->bindValue(':user_two_id',$user_two_id);
			$smtm->execute();			
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}	
	}*/
}
?>