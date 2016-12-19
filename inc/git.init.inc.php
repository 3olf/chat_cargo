<?php
/* CONNEXION */
$pdo = new PDO('mysql:host=localhost;dbname=;', '', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

/* SESSION */

session_start();

/* FUNCTIONS */
require_once("functions.inc.php"); 

$msg = "";
$super_control = false;
