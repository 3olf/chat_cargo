<?php
require_once("../inc/init.inc.php");

/* --------- DECONNEXION --------- */ 
if (isset($_GET['action']) && userConnected() && $_GET['action'] == 'deconnexion') 
{

		// Mise à jour de l'activité utilisateur
    $pdo->exec("UPDATE users SET last_seen= NOW(), statut='deconnecte' WHERE id_user = ".$_SESSION['user']['id_user']);

		// Detruit la session, s'execute à la fin du script
		session_destroy();	
		header('location:../index.php');
		// Bloque l'execution du script
		exit();		
}

/* --------- CONNEXION --------- */
if (isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['connexion'])) {
  
  /* EXTRACT */
  extract($_POST);

  $pseudo = htmlentities($pseudo, ENT_QUOTES);  
  $mdp = htmlentities($mdp, ENT_QUOTES);

   /* CONTROLS */
  $req = $pdo->query("SELECT id_user, pseudo FROM users WHERE pseudo='$pseudo' AND mdp=PASSWORD('$mdp')");

      // Vérification sur l'existence du pseudo demandé
  if ($req->rowCount() === 1) 
  {

  	// Si l'utilisateur existe, on créer un tableau array 'utilisateur' dans la $_SESSION
  	$_SESSION['user'] = array();

  	$user_session = $req->fetch(PDO::FETCH_ASSOC);

  	// On stock les éléments récupérés de la base de donnée dans $_SESSION['user']
  	foreach ($user_session as $key => $value) 
  	{
  		  $_SESSION['user'][$key] = $value;
  	}

    // Mise à jour de l'activité utilisateur
    $pdo->exec("UPDATE users SET last_seen=NOW(), statut ='en ligne' WHERE id_user = ".$_SESSION['user']['id_user']);

  	// Auquel on ajoute user_color (sert à mettre le pseudo de l'utilisateur en couleur)
  	$_SESSION['user']['user_color'] = randomColor();

    // Redirection 
    header('location:../index.php');

  }
  else 
  {
  	header('location:../index.php?error=connexion');  
  }
  exit();   
}	

/* --------- ENREGISTREMENT --------- */
// AJAX par défaut (fonctionne si JS est désactivé en PHP)
if (isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['register']))
{
  /* CONTROLS */
  $super_control = true;
    // PSEUDO //
      // length
  if(mb_strlen($_POST['pseudo']) < 4 || mb_strlen($_POST['pseudo']) > 14 ) 
  {
    $super_control = false;
  }

      // Regex
  if(!preg_match("#^[a-zA-Z0-9._-]+$#", $_POST['pseudo']))  // ^ debut de la chaine. $ fin de la chaine. + plusieurs caractères 
  { 
  	$super_control = false;
  }

    // PWD //
  if(!checkPassword($_POST['mdp'])) 
  {
    $super_control = false;
  }

  /* EXTRACT & ENTITIES */
  extract($_POST);

  $pseudo = htmlentities($pseudo, ENT_QUOTES); 
  // Fait bugguer PASSWORD() qui converti les caractères spéciaux 
  $mdp = htmlentities($mdp, ENT_QUOTES);    	

  /* INSERT */
    // PSEUDO //
  $req = $pdo->query("SELECT id_user FROM users WHERE pseudo='$pseudo'");
      // Vérification sur l'existence du pseudo demandé
  if ($req->rowCount() >=1 ) 
  {
    $super_control = false;
    // AJAX return
    echo 'false';
    exit();  
  } 

  if ($super_control === true)
  { 
    // Enregistrement utilisateur
   $register_nickname = $pdo->exec("INSERT INTO users (pseudo, mdp) VALUES ('$pseudo', PASSWORD('$mdp'))");

    //AJAX return
    echo 'true';
    exit();

    // javascript défaillant/désactivé
    // header('location:../index.php');
    // exit();
  }
  elseif($super_control === false)
  {
    // javascript défaillant/désactivé + erreur inscription
    header('location:../index.php.error=inscription');
  }
  exit();
}


// Redirige sur la page d'accueil pour rendre le service inaccesible
header('location:../index.php');