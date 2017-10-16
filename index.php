<?php
session_start();

$_SESSION['fromIndex'] = TRUE;

ini_set('error_reporting', E_ALL & ~E_NOTICE);

spl_autoload_register(function ($class_name) {
    include 'class/' . $class_name . '.php';
});

	// Kolla om cookie finns, annars sätt en.
	if (!isset($_COOKIE['julkalender']['decid'])) {
		// Skriv in användaren i db.
		$user = new user;
		$userID = $user->createUser();
	}
	else {
	  $userID = $_COOKIE[julkalender][decid];
	}
?>

<!DOCTYPE html>
<html lang = 'sv'>
	<head>
		<meta charset = 'UTF-8'>
		<meta http-equiv = 'X-UA-Compatible' content = 'IE=edge'>
			<meta name = 'viewport' content = 'width=device-width, initial-scale=1'>
		<title>TomteGo - Julkalender 2016</title>
		<link href = 'https://fonts.googleapis.com/css?family=Roboto' rel = 'stylesheet' type='text/css'>
		<link rel = 'stylesheet' type = 'text/css' href = 'css/julkalender.css'>
	</head>
	<body>
		<script src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
		<script src="https://use.fontawesome.com/ff5c7fb948.js"></script>


		<div class = 'box'>

			<!-- MENY / NAVIGERING -->
			<?php
			// Hämta poäng för användaren.
			$u = new user;
			$points = $u->getPoints($userID);
			?>
			<nav>
				<ul>
					<li><a href = 'index.php'>Start</a></li>
					<li><a href = 'index.php?sida=you'>Din sida</a></li>
					<li><a href = 'index.php?sida=list'>Tabell</a></li>
					<li><a href = 'index.php?sida=regler'>Regler</a></li>
					<li><a href = '#'><?php echo $points; ?></a></li>
				</ul>											
			</nav>

			<section id = 'spelplan'>

			<?php 

				if (date("Y-m-d") < "2016-12-01") {
					if ($_GET['sida'] == 'regler') {				
						include_once "regler.php";		
					}
					else {			
						echo "<section id = 'notOpenText'><h1>Snart...</h1>";
						echo "<p>Julkalendern startar torsdag den första december.</p></section>";
					}
				}
				elseif (date("Y-m-d") > "2016-12-24") {
					if ($_GET['sida'] == 'list') {				
						include_once "tabell.php";		
					}
					else {			
						echo "<section id = 'notOpenText'><h1>Tack för i år</h1>";
						echo "<p>Jultomten har hämtat alla gåvor, tack för din medverkan.</p></section>";
					}
				}
				else {
					if (isset($_GET['sida'])) {
						if ($_GET['sida'] == 'list')				
							include_once "tabell.php";					
						if ($_GET['sida'] == 'you')				
							include_once "you.php";					
						if ($_GET['sida'] == 'regler')				
							include_once "regler.php";					
					}
					else {
				?>


				<article id = 'mess'>
				</article>

				
					<?php
						// Ta reda på vad användaren har lagt sin röst på.
						$val = $_COOKIE['julkalender']['lastVote'];

						// Hämta dagens val.
						$gese = new getSelection;
						$todaysSelection = $gese->gs();
					?>


					<!-- Skyltar som visar dagens val. -->
					<span class = 'skyltram'>
						<a href = 'vote.php?vote=L' onclick = 'vote("L");' class = 'voteLinks'>
							<?php
								$regL = ($val == 'L')?"registeredVote":"";
							?>
							<span class = 'skylt <?php echo $regL; ?> ' id = "L"><i class = 'fa fa-<?php echo $todaysSelection[0]; ?> fa-2x' id = 'G<?php echo $todaysSelection[2]; ?>'></i></span>
						</a>
						<span class = 'resultat left'></span>
					</span>

					<div class = 'skyltram'>
						<a href = 'vote.php?vote=R' onclick = 'vote("R");' class = 'voteLinks' >
							<?php
								$regR = ($val == 'R')?"registeredVote":"";
							?>
							<div class = 'skylt <?php echo $regR; ?> ' id = 'R'><i class = 'fa fa-<?php echo $todaysSelection[1]; ?> fa-2x' id = 'G<?php echo $todaysSelection[3]; ?>'></i></div>
						</a>
						<span class = 'resultat right'></span>
					</div>

					<!-- Tomten -->
					<div id = 'gnome'><i class = 'fa fa-user fa-4x'></i></div>
				</section>

				<section id = 'giftCollection'>
					<?php 
						$gifts = new giftCollection; 
						$giftlist = $gifts->getGiftsSoFar();

						$shorty = new shortList;
						$lista = $shorty->getAktuellShortList($userID);
						$lista = $shorty->convertList();
						$position = NULL;
						$shortPosition = $shorty->controlList($gifts->giftRow);
						$i = 0;

						$wishy = new wishList;
						$wishlista = $wishy->getAktuellWishList($userID);
						$wishlista = $wishy->convertList();
						$wishPosition = $wishy->controlList($gifts->giftRow);

						foreach ($giftlist as $g) {
							$shortMarker = (!is_null($shortPosition) AND (($shortPosition == $i) OR ($shortPosition+1 == $i)))?' shortMarker':'';
							$wishMarker = (!is_null($wishPosition) AND (($wishPosition == $i) OR ($wishPosition+1 == $i) OR ($wishPosition+2 == $i)))?' wishMarker':'';
							echo "<div id = 'gifts'><i class = 'fa fa-" . $gifts->giftArray[$g] . " " . $shortMarker . $wishMarker . "' id = '" . ($i+1) . "'></i></div>";
							$i++;
						}

					?>
				</section>
				<section id = 'giftCollectionExplanation'>
					<p>Här ser du vad tomten samlat ihop. Den senaste gåvan är längst till höger i den nedersta raden. Lucia är <span class = 'brownie'>färgmarkerad</span>.</p>
				</section>

				<footer>
					<section id = 'aktuellShortlist'>
						<?php
							foreach ($lista as $item) {
								echo "<div><i class = 'fa fa-" . $gifts->giftArray[$item] . " fa-2x'></i></div>";
							}
						?>
						<!--
						<div><i class = 'fa fa-cube fa-2x'></i></div>
						<div><i class = 'fa fa-snowflake-o fa-2x'></i></div>
						-->
					</section>
					<section id = 'aktuellShortlistExplanation'>
						<p>Här ser du din aktuella korta önskelista. En fylld lista är värd 1 poäng.</span></p>
					</section>

					<section id = 'aktuellWishlist'>
						<?php
							foreach ($wishlista as $witem) {
								echo "<div><i class = 'fa fa-" . $witem . " fa-2x'></i></div>";
							}
						?>
	<!--			<div><i class = 'fa fa-snowflake-o fa-2x' aria-hidden='true'></i></div>
						<div><i class = 'fa fa-tree fa-2x'></i></div>
						<div><i class = 'fa fa-heart fa-2x'></i></div> -->
					</section>
					<section id = 'aktuellWishlistExplanation'>
						<p>Här ser du din aktuella långa önskelista. En fylld lista är värd 4 poäng.</span></p>
					</section>
				</footer>

				<?php
					} // Slut på else-if-satsen på rad 59.
				}
			?>

		</div>
	</body>
</html>
<script>
	function vote(direction) {
		var d = new Date();
		var tim = d.getHours();
		console.log(tim);
		if (tim >= 18) {
			$('.voteLinks').removeAttr("href");
		}
		else {
			$(".skylt").removeClass("registeredVote");
			console.log ("Du har röstat på " + direction);
			$("#" + direction).addClass("registeredVote");
		}
	}

	/* Växla mellan förklaring och tomtens gåvor */
	$('#giftCollectionExplanation').hide();
	$('#giftCollection, #giftCollectionExplanation').on('click',
		function() {
		  $('#giftCollection, #giftCollectionExplanation').toggle()
		}
	);

	/* Växla mellan förklaring och aktuell shortlist*/
	$('#aktuellShortlistExplanation').hide();
	$('#aktuellShortlist, #aktuellShortlistExplanation').on('click',
		function() {
		  $('#aktuellShortlist, #aktuellShortlistExplanation').toggle()
		}
	);

	/* Växla mellan förklaring och aktuell wishlist*/
	$('#aktuellWishlistExplanation').hide();
	$('#aktuellWishlist, #aktuellWishlistExplanation').on('click',
		function() {
		  $('#aktuellWishlist, #aktuellWishlistExplanation').toggle()
		}
	);

	$(document).ready(function(){
		if ( $('div#gifts i').hasClass('shortMarker') ) {
		  $('#mess').html("<p>Du har fått in en <a href = 'score.php?score=1' id='1pts' class = 'shortMarker'>kort önskelista</a>.");
		}
		if ( $('div#gifts i').hasClass('wishMarker') ) {
		  $('#mess').html("<p>Du har fått in en <a href = 'score.php?score=4' id='4pts' class = 'wishMarker'>lång önskelista</a>.");
		}
		/* Kolla om dagens val är avgjort. */
		var d = new Date();
		var dd = d.getDate();
		if($("#" + dd).length != 0) {
		  $('#mess').append("<p>Dagens röster är räknade, och det valda objektet är markerat med svart ram.</p>");
		}

		var tim = d.getHours();
		$.ajax({
			type:'POST',
			url:'getTodaysResult.php',
			dataType: 'json',
			cache: 'false',
			success:function(msg){
				if (tim >= 18) {
					$('.resultat.left').html(msg['left']);
					$('.resultat.right').html(msg['right']);
				}
				$('#G' + msg['selected']).addClass('blackBox');
				console.log(msg['selected']);
			}
		});
	});
</script>
