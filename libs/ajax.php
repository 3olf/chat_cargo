<?php
require_once("../inc/init.inc.php"); 



// Module d'enregistrement des messages en AJAX
if (isset($_POST["message"]) && isset($_SESSION['utilisateur'])) 
{
	// Mise à jour de l'activité utilisateur
	$pdo->exec("UPDATE users SET last_seen=NOW() WHERE id_user = ".$_SESSION['utilisateur']['id_user']);

	// Conversion en htlmlentities pour éviter les caractères dégueulasses
	$_POST["message"] = htmlentities($_POST["message"], ENT_QUOTES);

	// Requête d'enregistrement en base de donnée
	$register = $pdo->query("INSERT INTO messages (id_user, date_message, message) VALUES (".$_SESSION['utilisateur']['id_user'].", NOW(), '$_POST[message]')");
	exit();
}


// Module de chat (affichage des messages) en AJAX
if (isset($_SESSION['utilisateur']) && isset($_POST["action"]) && $_POST["action"] == "update")
{
	// Date à laquelle la requête a été effectuée (au format NOW)
	$date_query = $_POST["datemess"];

	// Requête JOIN de récupération de l'ensemble des messages + des détails utilisateur
	$req = $pdo->query("SELECT m.*, u.*  FROM messages AS m JOIN users AS u ON m.id_user = u.id_user WHERE date_message > '$_POST[datemess]'  ORDER BY date_message DESC LIMIT 12");
	$det = $req->fetchall(PDO::FETCH_ASSOC);

	// Boucle pour ajouter la couleur utilisateur au tableau multi dimensionnel récupéré du fetch de ma requête
	$longueur_det = count($det);
	for ($i = 0; $i < $longueur_det; $i++)
	{			
		if ($det[$i]['id_user'] == $_SESSION['utilisateur']['id_user'])
		{
			// Cas ou l'utilisateur récupéré de la BDD correspond à l'utilisateur connecté
			$det[$i]['usercolor'] = $_SESSION['utilisateur']['user_color'];
		}
		else
		{
			// Autres utilisateurs
			$det[$i]['usercolor'] = "";
		}		
	}

	// Renvoi des donnés sous forme JSON
	echo json_encode($det);
	exit();
}

// Requête AJAX uniquement pour starter le setInterval on refresh si l'utilisateur est connecté because I have no choice
if (isset($_POST["action"]) && $_POST["action"] == "starter")
{
	if(isset($_SESSION['utilisateur']))
	{
		echo 'true';
	}
	else
	{
		echo 'false';
	}
	exit();
}

// Redirige sur la page d'accueil pour rendre le service inaccesible
header('location:../index.php');