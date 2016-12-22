<?php
require_once("../inc/init.inc.php"); 



////////// Module d'enregistrement des messages en AJAX //////////
if (isset($_POST["message"]) && isset($_SESSION['user'])) 
{
	// Mise à jour de l'activité utilisateur
	$pdo->exec("UPDATE users SET last_seen=NOW(), statut='en ligne' WHERE id_user = ".$_SESSION['user']['id_user']);

	$_SESSION['user']['last_seen'] = date("Y-m-d H:i:s");

	// Conversion en htlmlentities pour éviter les caractères dégueulasses
	$_POST["message"] = htmlentities($_POST["message"], ENT_QUOTES);


	// Requête d'enregistrement en base de donnée
	$register = $pdo->exec("INSERT INTO messages (id_user, id_salon, date_message, message) VALUES (".$_SESSION['user']['id_user'].", ".$_SESSION['user']['id_salon'].",  NOW(), '$_POST[message]')");

	exit();
}
elseif(isset($_POST["message"]) && !userConnected())
{
	echo 'Vous devez vous connecter pour envoyer un message';
	exit();
}

////////// Module de chat (affichage des messages) en AJAX //////////
if (isset($_POST["action"]) && isset($_POST["numsalon"]) && $_POST["action"] == "update")
{
	// Date à laquelle la requête a été effectuée (au format NOW)
	$date_query = $_POST["datemess"];

	if(preg_match("#^[0-9]+$#", $_POST['numsalon']))
	{
		// Requête JOIN de récupération de l'ensemble des messages + des détails utilisateur
		$req = $pdo->query("SELECT m.*, u.*  FROM messages AS m JOIN users AS u ON m.id_user = u.id_user WHERE date_message > '$_POST[datemess]' AND m.id_salon = $_POST[numsalon] ORDER BY date_message DESC LIMIT 12");
		$det = $req->fetchall(PDO::FETCH_ASSOC);

		// Boucle pour ajouter la couleur utilisateur au tableau multi dimensionnel récupéré du fetch de ma requête
		if(isset($_SESSION['user']))
		{
			$longueur_det = count($det);
			for ($i = 0; $i < $longueur_det; $i++)
			{			
				if ($det[$i]['id_user'] == $_SESSION['user']['id_user'])
				{
					// Cas ou l'utilisateur récupéré de la BDD correspond à l'utilisateur connecté
					$det[$i]['usercolor'] = $_SESSION['user']['user_color'];
				}
				else
				{
					// Autres utilisateurs
					$det[$i]['usercolor'] = "";
				}		
			}
		}
	}

	// Renvoi des donnés sous forme JSON
	echo json_encode($det);
	exit();
}

////////// Redirige sur la page d'accueil pour rendre le service inaccesible //////////
header('location:../index.php');