<?php

// RANDOM COLOR GENERATOR
function randomColor() {
	$tab_lettres = array('a', 'b', 'c', 'd', 'e', 'f', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$couleur = "";
	for ($i = 0; $i < 6; $i++) {
		$nb_rand = rand(0, 15);
		$couleur .= $tab_lettres[$nb_rand];
	}
	return $couleur;
}

// USER AUTH
function userConnected() 
{
	if (isset($_SESSION['utilisateur']))
	{
		return true;
	}
	else 
	{
		return false;
	}
}

// PASSWORD CHECKER
function checkPassword($password) 
{
   $reg1='/[A-Z]/';  // majuscule
   $reg2='/[a-z]/';  // minuscule
   $reg3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // caractère spécial
   $reg4='/[0-9]/';  // chiffre

   // preg_match_all() compte le nombre d'occurence d'une expression régulière et place chaque occurence dans un tableau array défini en 3 ème argument (ici $tab) et renvoi un INT représentant le nombre d'occurence total.
   if(preg_match_all($reg1,$password, $tab)<1) return FALSE; // check si au moins une majuscule

   if(preg_match_all($reg2,$password, $tab)<1) return FALSE; // check si au moins une minuscule

   if(preg_match_all($reg3,$password, $tab)<1) return FALSE; // check si au moins un caractère spécial

   if(preg_match_all($reg4,$password, $tab)<1) return FALSE; // check si au moins un chiffre

   if(mb_strlen($password, 'utf-8')<8) return FALSE; // check si taille inférieure à 8
   
   return TRUE;
}