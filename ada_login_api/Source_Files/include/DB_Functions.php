<?php
ChromePhp::log("page: DB_Functions.php");
class DB_Functions {
	private $conn;

	//konstruktor
	function __construct() {
		require_once 'DB_Connect.php';
		$db = new DB_Connect();
		$this->conn = $db->connect();
	}
	//destruktor
	function __destruct() {

	}

	public function registerUser($username, $password, $email) {
		//user tabela: user_id, username, email, description
		//login tabela: login_id, user_id,password
		try {
			$db = new DB_Connect();

			//insert into user table
			$smtm = $this->$conn->prepare('INSERT INTO user (username, email, description) VALUES (:username, :email, :description)');
			$smtm->bindValue(':username',$username);
			$smtm->bindValue(':email',$email);
			$smtm->bindValue(':description',"");
			$smtm->execute();

			$user_id = $db->lastInsertId();

			//insert into login table
			$smtm = $this->$conn->prepare('INSERT INTO login (user_id, password) VALUES (:user_id, :password)');
			$smtm->bindValue(':user_id',$user_id);
			$smtm->bindValue(':password',$password);
			$smtm->execute(); 
			 
		} catch (PDOException $e) {
			Chrome
			//echo $e->getMessage();
		}

	}

	public function emailTaken($email) {
		try {
			$db = new DB_Connect();
			$smtm = $this->$conn->prepare('SELECT user_id FROM user WHERE email=:email LIMIT 1');
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

	public function usernameTaken($username) {
		try {
			//poglej, ce je username ze v bazi
			$db = new DB_Connect();
			$stmt = $this->$conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$stmt->bindValue(':username',$username);
			$smtm->execute();
			if($smtm->rowCount()>0) {
				return true;
			}
			return false;
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}

	public function getUserIDByName($username) {
		try {
			$db = new DB_Connect();
			$smtm = $this->$conn->prepare('SELECT user_id FROM user WHERE username=:username LIMIT 1');
			$smtm->bindValue(':username',$username);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result['user_id'];
			}
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}

	public function changeUserDescription($username,$newDescription) {
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('UPDATE user SET desctiption=:description WHERE username=:username');
			$smtm->bindValue(':description',$newDescription);
			$smtm->bindValue(':username',$username);
			$smtm->execute();
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
		
	}

	public function addPost($post_id, $poster_id, $postContent,$timeStamp, $favourite_counter) {
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('INSERT INTO post (poster_id, content, timestamp, favourite_counter) VALUES (:poster_id, :post_content, :timestamp, :favourite_counter)');
			$smtm->bindValue(:poster_id, $poster_id);
			$smtm->bindValue(':content', $postContent);
			$smtm->bindValue(':timestamp',$timeStamp);
			$smtm->bindValue(':favourite_counter',$favourite_counter);
			$smtm->execute();
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}

	//user klikne zvezdico
	public function likePost($userID, $postID, $timeStamp) {
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('INSERT INTO favourite (user_id, post_id, timestamp) VALUES (:user_id, :post_id, :timestamp)');
			$smtm->bindValue(':user_id',$userID);
			$smtm->bindValue(':post_id',$postID);
			$smtm->bindValue(':timestamp',$timeStamp);
			$smtm->execute();
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}

	//user odklikne zvezdico
	public function unlikePost($postID,$userID) {
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('DELETE FROM favourite WHERE post_id=:post_id AND user_id=:user_id');
			$smtm->bindValue('post_id',$postID);
			$smtm->bindValue('user_id',$userID);
			$smtm->execute();
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}

	//dobimo poste userja
	public function loadUserPosts($userID) {
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT * FROM post WHERE user_id=:user_id ORDER BY timestamp DESC');
			$smtm->bindValue(':user_id',$userID);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}

	//vrnemo prijatelje od neke osebe
	//v tabeli imamo id, user_one_id, user_two_id, status,
	//action_user_id
	//status pove a je prijateljstvo pending(0) ali pa potrjeno (1)
	//action user pa pove kdo je poslal zahtevo
	public function getFriends($userID) {
		//uzamem use user user_two_id-je, kjer je user_one_id enak
		//user_id. in vse user_one idje, kjer je user_two enak user_id
		try {
			$db = new DB_Connect();

			$smtm = $this->$conn->prepare('SELECT user_one_id, user_two_id FROM friend WHERE user_one_id=:user_id or user_two_id=:user_id AND status=1');
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
			$smtm = $this->$conn->prepare('SELECT * FROM user WHERE user_id IN :friends');
			$smtm->bindValue(:friends, $friends);
			$smtm->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);

			return $result;
		} catch (PDOException $e) {
			//echo $e->getMessage();
		}
	}
}
?>