<?php
require_once("init.inc.php"); 

if (userConnected()) 
{
?>
<div id="espace-login">
	<a href="libs/connect.php?action=deconnexion" class="btn-inverse-perso">Se déconnecter</a>
</div>
<?php
}
elseif (!userConnected())
{
?>
<div id="espace-register">
	<button class="btn-inverse-perso" data-btn="register">S'enregistrer</button>
</div>
<div id="espace-login">
	<form method="post" action="libs/connect.php" id="form-connexion">
		<input type="text" name="pseudo" id="pseudo-login" placeholder="Entrez votre pseudo">
		<input type="password" name="mdp" id="mdp-login" placeholder="Mot de passe">
		<input type="submit" name="connexion" value="Se connecter" class="btn-inverse-perso">
	</form>
</div>
<?php
}