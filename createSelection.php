<?php


include_once "class/uppkoppling.php";
include_once "facts.php";

// Slumpa fram dagens två alternativ.
function twoSlumps() {
	$rnd1 = floor(mt_rand(0,8)/2);

	// Kolla så att de inte är samma.
	do {
		$rnd2 = floor(mt_rand(0,8)/2);
	} while ($rnd1 == $rnd2);

	$slumptal[0] = $rnd1;
	$slumptal[1] = $rnd2;
	return $slumptal;
}

$slump = twoSlumps();
$leftGift = $slump[0];
$rightGift = $slump[1];

$connect = new uppkoppling;
$pdo = $connect->conn();

$STH = $pdo->prepare("
INSERT INTO selections 
(day, leftGift, rightGift) 
VALUES 
($dagensDatum, $leftGift, $rightGift)
");

try {
	$STH->execute();
}
catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
	exit;
}

// Skapa även en rad i votes-tabellen, så att folk kan rösta.
$STH = $pdo->prepare("
INSERT INTO votes 
(day, votesForLeft, votesForRight) 
VALUES 
($dagensDatum, 0, 0)
");

try {
	$STH->execute();
}
catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
	exit;
}

?>
