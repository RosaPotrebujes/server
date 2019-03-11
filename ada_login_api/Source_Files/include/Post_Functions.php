<?php
ChromePhp::log("page: Post_Functions.php");
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
		$this->conn = $db->close();
	}
	public function getPostById($post_id) {
		try {
			//poglej, ce je username ze v bazi
			$db = new DB_Connect();
			$stmt = $this->$conn->prepare('SELECT * FROM post WHERE post_id=:post_id LIMIT 1');
			$stmt->bindValue(':post_id',$post_id);
			$smtm->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!empty($result)) {
				return $result;
			}
		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function addPost($post) {
		$json_post = json_decode($post, true);
		try {
			//poglej, ce je username ze v bazi
			$db = new DB_Connect();
			$stmt = $this->$conn->prepare('INSERT INTO post (poster_id, content, timestamp) VALUES (:userId, :content, :timestamp)');
			$stmt->bindValue(':post_id',$json_post['post_id']);
			$smtm->bindValue(':content',$json_post['content']);
			$smtm->bindValue(':timestamp',$json_post['timestamp']);
			$smtm->execute();

		} catch(PDOException $e) {
			//echo $e->getMessage();
		}
	}
	public function deletePost($post_id) {

	}
	public function incrementFavouriteCounter($post_id) {

	}
	public function countNumFavourited($post_id) {

	}
	public function getUserPosts($user_id) {

	}
	public function getUserFavPosts($user_id) {

	}
	public function addToFavourites($user_id, $post_id) {

	}
	public function removeFromFavourites($user_id, $post_id) {

	}
	public function getHomePosts($user_id, $friends_ids) {

	}
?>