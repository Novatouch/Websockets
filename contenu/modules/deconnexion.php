<?php

session_start();


include_once("../config/config.php");

$chaine_connexion = "host=$host port=$port dbname=$dbname user=$user password=$password options='--client_encoding=UTF8'";
$bd_connexion = pg_connect($chaine_connexion);

if($bd_connexion == FALSE)
{

// connexion bd impossible
// pas d'actions
}
else
{
	if(!isset($_SESSION['id']))
    	{
		// pas de variables de sessions
		// pas d'actions
	}
	else
	{
		$id = $_SESSION['id'];

		// requete suppression tocken session
		$requete = "UPDATE webapp.authentification_insertion SET token='' WHERE id='$id'";
		$resultat = pg_exec($bd_connexion, $requete);

		// Si la requête à échouée redirection
		if($resultat == FALSE)
		{
			// pas d'actions
		}
		else
		{

		}
	}
}


// destruction des variables de sessions
session_destroy();

		// redirection de l'utilisateur
$host  = $_SERVER['HTTP_HOST'];
//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'Websockets/index.php';
header("Location: http://$host/$extra");



exit;

?>
