<?php

if (isset($_GET['vote'])) {
	$vote = $_GET['vote'];
}

$voteL = 0;
$voteR = 0;

$dagensDatum = date("d");
$nu = date("Y-m-d H:i:s");

if ($vote == "L")
	$voteL = 1;
else 
	$voteR = 1;

$lastVotingDay = date("d", strtotime($_COOKIE['julkalender']['lastVotingTime']));

// Kontrollera om användaren röstat tidigare, och då på samma alternativ.
if (($lastVotingDay != $dagensDatum) OR (($lastVotingDay == $dagensDatum) AND ($vote != $_COOKIE['julkalender']['lastVote']))) {

	include_once("class/uppkoppling.php");

	$connect = new uppkoppling;
	$pdo = $connect->conn();

	if (($vote == "L") AND (($vote != $_COOKIE['julkalender']['lastVote']) AND ($lastVotingDay == $dagensDatum))) {
		$voteR = -1;
	}

	if (($vote == "R") AND (($vote != $_COOKIE['julkalender']['lastVote']) AND ($lastVotingDay == $dagensDatum))) {
		$voteL = -1;
	}

	$STH = $pdo->prepare("
	UPDATE votes SET
	votesForLeft = votesForLeft + :vl, votesForRight = votesForRight + :vr
	WHERE day = $dagensDatum
	");
	$STH->bindParam(":vl", $voteL);
	$STH->bindParam(":vr", $voteR);

	try {
		$STH->execute();
	}
	catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
		exit;
	}

	setcookie("julkalender[lastVotingTime]", $nu, time()+3600*24*60, "/");
	setcookie("julkalender[lastVote]", $vote, strtotime('today 23:59'), '/');		
}

echo "<script type='text/javascript'>
document.location.href = 'index.php';
</script>";
exit;

?>
