<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);
class user extends facts {
	
	public function createUser() {
		
		$shortList = $this->getListNumber("shortListNumber");
		$wishList = $this->getListNumber("wishListNumber");

		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
INSERT INTO user 
(shortlists, wishlists, lastVisited, lastVote, points)
VALUES
($shortList, $wishList, NOW(), ' ',0)");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		

		$userID = $pdo->lastInsertId();

		// Sätt kakor.
		setcookie("julkalender[aktuellWishlist]", $wishList, time()+3600*24*60, "/");
		setcookie("julkalender[aktuellShortlist]", $shortList, time()+3600*24*60, "/");
		setcookie("julkalender[shortlistList]", $shortList);
		setcookie("julkalender[wishlistList]", $wishList);
		setcookie("julkalender[lastVisited]", date("Y-m-d"),time()+3600*24*60, "/");
		setcookie("julkalender[lastVotingTime]", date("Y-m-d H:i:s"),time()+3600*24*60, "/");
		setcookie("julkalender[decid]", $userID, time()+3600*24*60, "/"); // Låt cookien vara två månader för säkerhets skull.
		setcookie("julkalender[startReset]", "1",time()+3600*24*60, "/");

		return $userID;
	}

	public function getPoints($uid) {
		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
SELECT points FROM user
WHERE userID = :uid
		");
		$STH->bindParam(":uid", $uid);

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		
		$row = $STH->fetch(PDO::FETCH_ASSOC);
		return $row['points'];
	}

}
?>
