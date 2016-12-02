<?php
require_once("inc/init.inc.php"); 

/////// TODO ///////
// Déconnecter l'utilisateur après un laps de temps + header location


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Chat Cargo</title>
		<link href="css/style.css" rel="stylesheet">
		<script
		src="https://code.jquery.com/jquery-3.1.1.min.js"
		integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
		crossorigin="anonymous"></script>		
		<script type="text/javascript" src="js/script.js"></script>		
	</head>
	<body>
		<header>
			<?php include('inc/connexion.inc.php') ?>
			<div class="clear"></div>
		</header>
		<nav>
			<ul class="menu">
				<li><a href="#">Menu 1</a></li>
				<li><a href="#">Menu 2</a></li>
				<li><a href="#">Menu 3</a></li>
				<li><a href="#">Menu 4</a></li>
			</ul>
		</nav>
		<div class="clear"></div>
		<aside>
			<fieldset>
				<legend>Connexion au chat</legend>
				<?= $msg ?>
			</fieldset>
		</aside>
		<section>
			<fieldset>
				<legend>Chat en AJAX</legend>				
				<?php // echo "<pre>"; var_dump($_SESSION['utilisateur']); echo "</pre>"; // L'erreur php bloque l'execution des scripts en footer ?>
				<form method="post" action="libs/ajax.php" id="form-message">
					<textarea name="message" placeholder="Clavarder..."></textarea>
					<input type="submit" name="envoyermess" value="Envoyer" class="btn-perso" id="btn-chat">
				</form>
				<div class="clear"></div>
				<div id="main-content">

				</div>		
			</fieldset>			
		</section>
		<div class="clear"></div>
		<footer></footer>
	</body>
</html>