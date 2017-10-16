<?php
include_once("class/uppkoppling.php");
include_once("class/facts.php");

$fact = new facts;
$ga = $fact->getGifts();

$connect = new uppkoppling;
$pdo = $connect->conn();

$userID = $_COOKIE[julkalender][decid];

// Ska vi ändra namnet?
if (isset($_POST['changeName'])) {
		
	$namn = trim($_POST['namn']); 

	$STH = $pdo->prepare("
	UPDATE user
	SET namn = '$namn'
	WHERE userID = :uid
	");
	$STH->bindParam(":uid", $userID);

	try {
		$STH->execute();
	}
	catch (PDOException $e) {
		echo "UpdError: " . $e->getMessage();
		exit;
	}
	
}


$STH = $pdo->prepare("
SELECT * 
FROM user 
WHERE userID = :uid
");

$STH->bindParam(":uid", $userID);

try {
	$STH->execute();
}
catch (PDOException $e) {
	echo "TabError: " . $e->getMessage();
	exit;
}

$row = $STH->fetch(PDO::FETCH_ASSOC);

if(!is_null($row['namn'])) {
	$namn = $row['namn'];
}
else {
	$namn = $userID;
}

echo "<div class = 'tabber'><form action = 'index.php?sida=you' method = 'post'>";
echo "<label for = 'namn'>Namn </label>";
echo "<input type = 'text' class = 'namn' name = 'namn' value = '$namn' />";
echo "<button type = 'submit' name = 'changeName'>Ändra</button</form></div>";
echo "<div class = 'tabber'><span class = 'namn'>Korta listor</span></div>";

$shortListArray = explode(",", $row['shortlists']);

foreach ($shortListArray as $short) {
	$lista = $short - 1000;
	$lista = base_convert($lista,10,4);

	while (strlen($lista) < 2) {
		$lista = "0" . $lista;
	} 

	echo "<section id = 'aktuellShortlist'>";

	for ($i = 0; $i < strlen($lista); $i++) {
		$listItem = $lista[$i];
		echo "<div>";
			echo "<i class = 'fa fa-" . $ga[$listItem] . "' ></i>";
		echo "</div>";
	}

	echo "</section>";

}

echo "<div class = 'tabber'><span class = 'namn'>Långa listor</span></div>";

$wishListArray = explode(",", $row['wishlists']);
foreach ($wishListArray as $wish) {

	$lista = base_convert($wish,10,4);

	while (strlen($lista) < 3) {
		$lista = "0" . $lista;
	} 

	echo "<section id = 'aktuellWishlist'>";

	for ($i = 0; $i < strlen($lista); $i++) {
		$listItem = $lista[$i];
		echo "<div>";
			echo "<i class = 'fa fa-" . $ga[$listItem] . "' ></i>";
		echo "</div>";
	}

	echo "</section>";
}
?>
