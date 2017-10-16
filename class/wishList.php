<?php
include_once "class/facts.php";

class wishList extends facts {

	public $wishListNumber = 0;
	public $listItems = array();
	public $tempo;

	public function getAktuellWishList($user) {
		
		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
SELECT wishLists FROM user WHERE userID = $user
		");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		

		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$wishLists = $row['wishLists'];
		//echo "b" . $shortLists;
		$wishListArray = explode(",", $wishLists);
		$length = count($wishListArray) - 1;
		//echo "k" . $length;
		$this->wishListNumber = (int)$wishListArray[$length];

		return $this->wishListNumber;

	}

	public function convertList() {
		$this->tempo = base_convert($this->wishListNumber,10,4);
		while (strlen($this->tempo) < 3) {
			$this->tempo = "0" . $this->tempo;
		} 

		for ($i = 0; $i < strlen($this->tempo); $i++) {
			$where = $this->tempo[$i];
			$this->listItems[] = $this->giftArray[$where];			
		}
		return $this->listItems;
	}

	public function controlList($gifts) {
		// Kontrollera om hela listan finns med bland tomtens gåvor.
		if (strpos($gifts, $this->tempo) !== FALSE) {
			$this->hitPosition = strpos($gifts, $this->tempo); 		
			//echo "G:" . $gifts . " T:" . $this->tempo;		
		}
		return $this->hitPosition;
	}

}
?>
