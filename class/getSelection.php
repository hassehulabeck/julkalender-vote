<?php

class getSelection extends facts{

	public $retur = array();	

	public function gs() {
		
		$ga = $this->getGifts();

		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT leftGift, rightGift FROM selections WHERE day = $this->dagensDatum");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "GetError: " . $e->getMessage();
			exit;
		}
		
		$row = $STH->fetch(PDO::FETCH_ASSOC);
		$giftNumberLeft = $row['leftGift'];
		$giftNumberRight = $row['rightGift'];
		$this->retur[0] = $ga[$giftNumberLeft];
		$this->retur[1] = $ga[$giftNumberRight];
		$this->retur[2] = $giftNumberLeft;
		$this->retur[3] = $giftNumberRight;

		return $this->retur;
	}
}
?>
