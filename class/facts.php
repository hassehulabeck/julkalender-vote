<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);

class facts {
	
	public $giftArray = array("tree", "heart", "snowflake-o", "cube", "question");
	public $dagensDatum = 0;
	public $listNumber = 0;

	public function __construct() {
		include_once "uppkoppling.php";
		$this->dagensDatum = date("j");
	}

	public function getGifts() {
		return $this->giftArray;
	}

	public function getListNumber($type = "") {
		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
SELECT $type FROM options
");
		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		
		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$this->listNumber = $row[$type];

		// Justera värdet på respektive lista
		if ($type == 'wishListNumber') {
			$STH = $pdo->prepare("UPDATE options 
   SET wishListNumber = CASE WHEN wishListNumber = 63 THEN 0 ELSE wishListNumber + 1 END");
		}
		else {
			$STH = $pdo->prepare("UPDATE options 
   SET shortListNumber = CASE WHEN shortListNumber = 1015 THEN 1000 ELSE shortListNumber + 1 END");			
		}
		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		

		return $this->listNumber;
	}

}
?>
