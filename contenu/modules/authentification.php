<?php

include_once("fonction.php");
include_once("../config/config.php");

// Ce module vérifie l'identité de l'utilisateur créer des varaibles de sessions et renvoie un message sous format JSON

$chaine_connexion = "host=$host port=$port dbname=$dbname user=$user password=$password options='--client_encoding=UTF8'";
$bd_connexion = pg_connect($chaine_connexion);

if($bd_connexion == FALSE)
{
    $message['message']="echec";
    $message['debug']="Connexion à la base de donnée impossible";
}
else
{
    
    // récupération des variables de sessions contenant le mot de passe et l'identifiant
    if(!isset($_GET['identifiant']) || !isset($_GET['pass']))
    {
        // erreur reception variable
        $message['message']="echec";
        $message['debug']="Aucune donnée d'authentification n'a été transmise au serveur";
    }
    else
    {
        // sécurisation des données
        $identifiant = pg_escape_string($_GET['identifiant']);
        $mdp = pg_escape_string($_GET['pass']);

        // requête à la base pour récupérer le mot de passe de l'utilisateur
        $requete = "SELECT id, pass, role FROM webapp.authentification WHERE identifiant='$identifiant'";
        $resultat = pg_exec($bd_connexion, $requete);

        // Si la requête à échouée redirection
        if($resultat == FALSE)
        {
            $message['message']="echec";
            $message['debug']="La requete à la base de donnée à échouée";
        }
        else
        {

            // regarde le nombre de résultat
            $nbligne =pg_numrows($resultat);
            if ($nbligne == 0)
            {
                $message['message']="echec";
                $message['debug']="Mauvais identifiant ou mot de passe.";
            }
            else
            {
                $tableau = pg_fetch_array($resultat,0, PGSQL_ASSOC);


                //verification du mot de passe
                $hash_bd = $tableau['pass'];


                if(ValidatePassword($mdp, $hash_bd) == true)
                {
                    // génération token authentification pour la partie websocket
                    $token=GenerationToken();
                    $id=$tableau['id'];
                    
                    // enregistrement de la clé de session dans la base de donnée
                    $requete = "UPDATE webapp.authentification_insertion SET token='$token' WHERE id='$id'";
                    $resultat = pg_exec($bd_connexion, $requete);
                    
                    if($resultat == FALSE)
                    {
                        $message['message']="echec";
                        $message['debug']="La requete à la base de donnée à échouée";
                    }
                    else
                    {
                        //creation variable environement
                        $_SESSION['role']=$tableau['role'];
                        $_SESSION['token']=$token;
                        $_SESSION['id']=$id;

                        $message['message']="succes";
                    }
                }
                else
                {
                    $message['message']="echec";
                    $message['debug']="Mauvais identifiant ou mot de passe.";
                }
            }
        }
    }

    // deconnexion bd
    pg_close($bd_connexion);
}

// envoi message
echo json_encode($message);

?>