<?php
include_once "class/facts.php";

class shortList extends facts {

	public $shortListNumber = 0;
	public $listItems = array();
	public $tempo;
	public $hitPosition;

	public function getAktuellShortList($user) {
		
		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
SELECT shortLists FROM user WHERE userID = $user
		");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}		

		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$shortLists = $row['shortLists'];
		//echo "b" . $shortLists;
		$shortListArray = explode(",", $shortLists);
		$length = count($shortListArray) - 1;
		//echo "k" . $length;
		$this->shortListNumber = (int)$shortListArray[$length];

		return $this->shortListNumber;

	}

	public function convertList() {
		$lista = $this->shortListNumber - 1000;
		//echo $lista;
		$this->tempo = base_convert($lista,10,4);
		//echo $tempo;		

		while (strlen($this->tempo) < 2) {
			$this->tempo = "0" . $this->tempo;
		} 

		for ($i = 0; $i < strlen($this->tempo); $i++) {
			$this->listItems[] = $this->tempo[$i];
			//echo $where . "-";
			// = $this->giftArray[$where];			
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
