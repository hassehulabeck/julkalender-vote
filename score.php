<?php
session_start();

if(!isset($_SESSION['fromIndex']))
	die('Nej tack.');

if (isset($_GET['score'])) {
	$score = $_GET['score'];
}
else {
	$score = 0;
}

include_once("class/uppkoppling.php");
include_once("class/shortList.php");
include_once("class/wishList.php");
include_once("class/giftCollection.php");

$connect = new uppkoppling;
$pdo = $connect->conn();


// Kontrollera om användaren ska ha poäng.
$number = NULL;
$gifty = new giftCollection;
if ($score == 1) {
	$shorty = new shortList;
	$shorty->getAktuellShortList($_COOKIE['julkalender']['decid']);
	$shorty->convertList();
	$gifts = $gifty->getGiftsSoFar();
	$number = $shorty->controlList($gifty->giftRow);
}
if ($score == 4) {
	$wishy = new wishList;
	$wishy->getAktuellWishList($_COOKIE[julkalender][decid]);
	$wishy->convertList();
	$gifts = $gifty->getGiftsSoFar();
	$number = $wishy->controlList($gifty->giftRow);
}

if (!is_null($number)) {
	$STH = $pdo->prepare("
	UPDATE user SET
	points = points + $score
	WHERE userID = :uid 
	");
	$STH->bindParam(":uid", $_COOKIE['julkalender']['decid']);

	try {
		$STH->execute();
	}
	catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
		exit;
	}

	// Get a new list for the user.
	$typ = ($score == 1)?'shortListNumber':'wishListNumber';

	include_once ("class/facts.php");

	$fact = new facts;
	$newListNumber = $fact->getListNumber($typ);

	$typ = substr($typ,0,-6) . "s";

	$STH = $pdo->prepare("
	UPDATE user SET
	$typ = CONCAT($typ, ',', $newListNumber)
	WHERE userID = :uid 
	");
	$STH->bindParam(":uid", $_COOKIE['julkalender']['decid']);

	try {
		$STH->execute();
	}
	catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
		exit;
	}
}

echo "<script type='text/javascript'>
document.location.href = 'index.php';
</script>";
exit;

?>
