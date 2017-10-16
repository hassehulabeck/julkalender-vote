<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE);


class giftCollection extends facts{

	public $retur = array();	
	public $giftRow;

	public function getGiftsSoFar() {
		
		$ga = $this->getGifts();

		//include_once "facts.php";
		$connect = new uppkoppling();
		$pdo = $connect->conn();

		$STH = $pdo->prepare("SELECT gift FROM gnomeway ORDER BY day");

		try {
			$STH->execute();
		}
		catch (PDOException $e) {
			echo "GiftError: " . $e->getMessage();
			exit;
		}
		
		while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
			$giftNumber = $row['gift'];
			$this->giftRow .= $row['gift'];
			$gift = $ga[$giftNumber];
			$this->retur[] = $row['gift'];
		}
		return $this->retur;
	}
}
?>
