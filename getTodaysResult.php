<?php
include_once("class/voteCounter.php");

$cv = new voteCounter;
$antal = $cv->getVoteNumbers();
$gr = $cv->getResults();
echo json_encode($gr);
?>
