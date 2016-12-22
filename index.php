<?php
require_once("inc/init.inc.php");

/* TODO : closure pour faire un setInterval qui récupère les membres. Ajouter un timeout de 10 min */

// Pour conserver le salon en cours d'utilisation 
if(isset($_SESSION['user']))
{
	$numsalon = $_SESSION['user']['id_salon'];
	$nomsalon = $_SESSION['user']['nom'];
}
else
{
	$numsalon = 2;
	$nomsalon = 'General';	
}


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
			<fieldset id="aside-list-chat">
				<legend>Connexion au chat</legend>
				<div id="list-salons">
					<h4>Liste des salons</h4>
					<ul>
					</ul>
				</div>
				<div id="list-connectes">
					<h4>Connectés sur <?= $nomsalon ?></h4>
					<ul>
					</ul>
				</div>				
			</fieldset>
		</aside>
		<section>
			<fieldset id="section-chat" data-salon="<?= $numsalon ?>">
				<legend><?= $nomsalon ?></legend>				
				<?php // echo "<pre>"; var_dump($_SESSION['user']); echo "</pre>"; // L'erreur php bloque l'execution des scripts en footer ?>
				<div id="main-content">

				</div>		
			</fieldset>
			<fieldset id="section-clavardeur">
				<legend>Clavardeur</legend>
				<form method="post" action="" id="form-message">
					<textarea name="message" placeholder="Clavarder..." rows="4"></textarea>
					<input type="submit" name="envoyermess" value="Envoyer" class="btn-perso" id="btn-chat">
				</form>
				<div class="clear"></div>				
			</fieldset>			
		</section>
		<div class="clear"></div>
		<footer></footer>
	</body>
</html>