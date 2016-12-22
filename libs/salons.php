<?php
require_once("../inc/init.inc.php"); 

////////// Affichage des salons //////////
if (isset($_POST["action"]) && $_POST["action"] == "listSalons")
{
	$req = $pdo->query("SELECT id_salon, nom FROM salons");
	$salons = $req->fetchall(PDO::FETCH_ASSOC);

	// Renvoi des donnés sous forme JSON
	echo json_encode($salons);
	exit();
}
// Changement de salon
if (isset($_POST["salon"]) && isset($_POST["nom"])) 
{
	$numsalon = substr($_POST["salon"], -1);
	$nomsalon = $_POST["nom"];

	$json = array('num' => $numsalon, 'nom' => $nomsalon);
	echo json_encode($json);

	if(isset($_SESSION['user']))
		{
		// Mise à jour de l'activité utilisateur en BDD et en session
	    $req = $pdo->prepare("UPDATE users SET last_seen= NOW(), statut='en ligne', id_salon= :salon WHERE id_user = :user ");

		$req->bindParam(':user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
		$req->bindParam(':salon', $numsalon, PDO::PARAM_INT);

		$req->execute();

			// Session
		$_SESSION['user']['last_seen'] = date("Y-m-d H:i:s");
		$_SESSION['user']['id_salon'] = (int)$numsalon;
		$_SESSION['user']['nom'] = $nomsalon;
	}

	exit();
}

////////// Affichage des connectés //////////
if (isset($_POST["action"]) && $_POST["action"] == "listConnectes") 
{
	$_POST["salon"] = (int)$_POST["salon"];

	$req = $pdo->query("SELECT u.pseudo, TIMESTAMPDIFF(MINUTE, u.last_seen, NOW()) AS last_action FROM users AS u JOIN salons AS s ON u.id_salon = s.id_salon WHERE u.statut = 'en ligne' AND TIMESTAMPDIFF(MINUTE, u.last_seen, NOW()) < '10' AND u.id_salon = ".$_POST["salon"]." GROUP BY u.id_user");
	
	$users_connected = $req->fetchAll(PDO::FETCH_ASSOC);

	$tab = array();
	foreach ($users_connected as $user_connected) {
		$tab[] .= $user_connected['pseudo'];
	}

	echo json_encode($tab);
	exit();
}

////////// Redirige sur la page d'accueil pour rendre le service inaccesible //////////
header('location:../index.php');

// $req = $pdo->query("SELECT u.pseudo, TIMESTAMPDIFF(MINUTE, u.last_seen, NOW()) AS last_action FROM users AS u JOIN salons AS s ON u.id_salon = s.id_salon WHERE u.id_salon = 1 AND TIMESTAMPDIFF(MINUTE, u.last_seen, NOW()) < '10' GROUP BY u.id_user");
// $users_connected = $req->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; var_dump($users_connected); echo '</pre>';