<?php
include_once("class/uppkoppling.php");

$connect = new uppkoppling;
$pdo = $connect->conn();


$STH = $pdo->prepare("
SELECT * FROM user ORDER BY points DESC
");

try {
	$STH->execute();
}
catch (PDOException $e) {
	echo "TabError: " . $e->getMessage();
	exit;
}
$i = 1;
while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
	if ($row['namn'] == '') {
		$namn = "User " . $row['userID'];
	}
	else {
		$namn = $row['namn'];
	}
		
	echo "<div class = 'tabber'>";
	echo "<span class = 'siffra'>" . $i . "</span>";	
	echo "<span class = 'namn'>" . $namn . "</span>";
	echo "<span class = 'points'>" . $row['points'] . "</span>";
	echo "</div>";

	$i++;
}

?>
