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
	exit();
}

////////// Redirige sur la page d'accueil pour rendre le service inaccesible //////////
header('location:../index.php');