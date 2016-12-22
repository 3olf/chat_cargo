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

	// Mise à jour de l'activité utilisateur en BDD et en session
    $req = $pdo->prepare("UPDATE users SET last_seen= NOW(), statut='en ligne', id_salon= :salon WHERE id_user = :user ");

	$req->bindParam(':user', $_SESSION['user']['id_user'], PDO::PARAM_INT);
	$req->bindParam(':salon', $numsalon, PDO::PARAM_INT);

	$req->execute();

		// Session
	$_SESSION['user']['last_seen'] = date("Y-m-d H:i:s");
	$_SESSION['user']['id_salon'] = (int)$numsalon;
	$_SESSION['user']['nom'] = $nomsalon;

	// Suppression utilisateur de l'ancien salon
	exit();
}

////////// Redirige sur la page d'accueil pour rendre le service inaccesible //////////
header('location:../index.php');