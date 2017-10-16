<?php

include_once ("class/facts.php");

class voteCounter extends facts{

	public $leftVotes;
	public $rightVotes;
	public $select;
	public $result = array();

	public function count() {
		$this->getVoteNumbers();
		$this->setSelected();
	}

	public function getVoteNumbers() {
		$connect = new uppkoppling;
		$pdo = $connect->conn();

		$STH = $pdo->prepare("
		SELECT votesForLeft, votesForRight 
		FROM votes 
		WHERE day = '$this->dagensDatum'
		");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			exit;
		}

		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$this->leftVotes = $row['votesForLeft'];
		$this->rightVotes = $row['votesForRight'];
	}

	public function getResults(){
		$this->result['left'] = $this->leftVotes;
		$this->result['right'] = $this->rightVotes;

		$connect = new uppkoppling;
		$pdo = $connect->conn();
		$STH = $pdo->prepare("
SELECT gift FROM gnomeway
WHERE day = $this->dagensDatum");
		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Edrror: " . $e->getMessage();
			exit;
		}

		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$this->result['selected'] = $row['gift'];

		return $this->result;
	}


	public function setSelected() {
		$connect = new uppkoppling;
		$pdo = $connect->conn();
		
		if ($this->leftVotes == $this->rightVotes) {
			$slump = mt_rand(0,1);
			$leftOrRight = array("leftGift", "rightGift");
			$this->select = $leftOrRight[$slump];
		}

		if ($this->leftVotes > $this->rightVotes) {
			$this->select = "leftGift";		
		}

		if ($this->leftVotes < $this->rightVotes) {
			$this->select = "rightGift";		
		}



		// Update selection med majoritetens val.
		$STH = $pdo->prepare("
UPDATE selections SET
selectedGift = $this->select
WHERE day = $this->dagensDatum");
		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Edrror: " . $e->getMessage();
			exit;
		}


		// Om frågetecknet (4) har fått flest röster, ändra det till något annat.
		$STH = $pdo->prepare("
SELECT selectedGift, leftGift, rightGift FROM selections
WHERE day = $this->dagensDatum");
		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "Edrror: " . $e->getMessage();
			exit;
		}

		$row = $STH->fetch(PDO::FETCH_ASSOC);
		if ($row['selectedGift'] == 4) {
			$avoidGift = ($row['leftGift'] != 4)?$row['leftGift']:$row['rightGift'];
			do {
				$newGift = mt_rand(0,3);	
			} while ($newGift == $avoidGift);


			$STH = $pdo->prepare("INSERT INTO gnomeway 
												(day, gift)
												VALUES
												($this->dagensDatum, $newGift)
												ON DUPLICATE KEY UPDATE  
												gift = $newGift");
			try {
				$STH->execute();
			}
			catch (PDOException $e) {
				echo "Edrror: " . $e->getMessage();
				exit;
			}
		}
		else {

			// Update gnomeway med den valda gåvan.
			$STH = $pdo->prepare("
	INSERT INTO gnomeway 
	(day, gift)
	VALUES
	($this->dagensDatum, (SELECT $this->select FROM selections WHERE day = $this->dagensDatum))
	ON DUPLICATE KEY UPDATE  
	gift = (SELECT $this->select FROM selections WHERE day = $this->dagensDatum)");
			try {
				$STH->execute();
			}
			catch (PDOException $e) {
				echo "Errddor: " . $e->getMessage();
				exit;
			}
		}
	}
}
?>
