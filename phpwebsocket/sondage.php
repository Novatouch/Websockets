#!/php -q
<?php  
// Run from command prompt > php -q chatbot.demo.php
include "websocket.class.php";

// Extended basic WebSocket as ChatBot
class ChatBot extends WebSocket{
    function process($user,$msg){

        // compte bd
        $host='127.0.0.1';
        $port='5432';
        $dbname='webappbd'; 
        $userbd='webappbd_update'; 
        $password='vS6yKz64';
        $message=[];

        // decodage du token et de l'id de l'utilisateur
        $msg_decoded=json_decode($msg,true);

        echo $msg;

        if (!isset($msg_decoded["id"]) || !isset($msg_decoded["token"]) || !isset($msg_decoded["requete"]))
        {
            unset($message);
            $message['requete']="inconnue";
            $message['statut']="echoue";
            $message['debug']="Requête malformée envoyée par le client";

            $this->send($user->socket,json_encode($message));
        }
        else
        {
            $id=$msg_decoded["id"];
            $token=$msg_decoded["token"];
            $requete=$msg_decoded["requete"];

            // connexion BD
            $chaine_connexion = "host=$host port=$port dbname=$dbname user=$userbd password=$password options='--client_encoding=UTF8'";

            $bd_connexion = pg_connect($chaine_connexion);

            if($bd_connexion == FALSE)
            {
                unset($message);
                $message['requete']=$requete;
                $message['statut']="echoue";
                $message['debug']="Connexion à la base de donnée impossible";

                $this->send($user->socket,json_encode($message)); 
            }
            else
            {
                // vérification de l'id et du token + récupération du role de l'utilisateur
                $id=pg_escape_string($id);
                $requete_sql = "SELECT token FROM webapp.authentification WHERE id='$id'";
                $resultat = pg_exec($bd_connexion, $requete_sql);

                // Si la requête a échouée redirection
                if($resultat == FALSE)
                {
                    unset($message);
                    $message['requete']=$requete;
                    $message['statut']="echoue";
                    $message['debug']="Une requête d'authentification auprès du serveur de base de donnée à échouée";

                    $this->send($user->socket,json_encode($message)); 
                }
                else
                {
                    // verification de l'existance de l'utilisateur
                    $nbligne =pg_numrows($resultat);
                    if ($nbligne == 0)
                    {
                        unset($message);
                        $message['requete']=$requete;
                        $message['statut']="echoue";
                        $message['debug']="L'utilisateur n'est pas authentifié";

                        $this->send($user->socket,json_encode($message)); 
                    }
                    else
                    {
                        $tableau = pg_fetch_array($resultat,0, PGSQL_ASSOC);

                        //verification du mot de passe
                        $token_bd = $tableau['token'];


                        // verification de l'existance de l'utilisateur
                        if ($token != $token_bd)
                        {
                            unset($message);
                            $message['requete']=$requete;
                            $message['statut']="echoue";
                            $message['debug']="L'utilisateur n'est pas authentifié";

                            $this->send($user->socket,json_encode($message)); 
                        }
                        else
                        {

                            switch($requete){
                                case ($requete == "lister_sondage_en_cours" || $requete == "lister_question_reponse_sondage") :

                                $verification="ok";

                                switch($requete){
                                    case ("lister_question_reponse_sondage") :

                                    if(!isset($msg_decoded["id_sondage"])){
                                        $verification="nok";
                                    }

                                    // verification de la présence de la variable contenant l'identifiant du sondage
                                    break;
                                }

                                // creation requete SQL
                                if($verification != "ok")
                                {
                                    unset($message);
                                    $message['requete']=$requete;
                                    $message['statut']="echoue";
                                    $message['debug']="Les données fournis par l'utilisateur étaient éronnés";

                                    $this->send($user->socket,json_encode($message)); 
                                }
                                else
                                {


                                    switch($requete){
                                        case ("lister_sondage_en_cours") :
                                        $requete_sql = "SELECT json FROM webapp.vue_sondage;";
                                        break;
                                        case ("lister_question_reponse_sondage") :

                                        $id_sondage=pg_escape_string($msg_decoded["id_sondage"]);
                                        $requete_sql = "SELECT lister_sondage_id('$id_sondage','$id') AS json;";
                                        break;
                                    }

                                    $resultat = pg_exec($bd_connexion, $requete_sql);

                                    // Si la requête a échouée redirection
                                    if($resultat == FALSE)
                                    {
                                        unset($message);
                                        $message['requete']=$requete;
                                        $message['statut']="echoue";
                                        $message['debug']="Une requête de récupération de donnée auprès du serveur de base de donnée à échouée"; 

                                        $this->send($user->socket,json_encode($message)); 
                                    }
                                    else
                                    {
                                        $tableau = pg_fetch_array($resultat,0, PGSQL_ASSOC);

                                        unset($message);
                                        $resultat_json=$tableau['json'];
                                        $message='{"requete":"'.$requete.'","statut":"ok","data":'.$resultat_json.'}';

                                        $this->send($user->socket,$message); 
                                    }
                                }
                                break;

                                case "envoyer_reponse_questionnaire" :

                                // envoyer les données dans la base de donnée
                                $requete_sql = "SELECT enregistrer_reponse('".$msg."'::JSON);";


                                $resultat = pg_exec($bd_connexion, $requete_sql);

                                // Si la requête a échouée redirection
                                if($resultat == FALSE)
                                {
                                    unset($message);
                                    $message['requete']=$requete;
                                    $message['statut']="echoue";
                                    $message['debug']="Une requête de récupération de donnée auprès du serveur de base de donnée à échouée"; 

                                    $this->send($user->socket,json_encode($message)); 
                                }
                                else
                                {
                                    $id_sondage=$msg_decoded['data']['id_sondage'];

                                    $requete_sql="SELECT lister_sondage_id_update(".$id_sondage.") AS json;";


                                    $resultat = pg_exec($bd_connexion, $requete_sql);
                                    if($resultat == FALSE)
                                    {
                                        unset($message);
                                        $message['requete']=$requete;
                                        $message['statut']="echoue";
                                        $message['debug']="L'envoi au serveur des votes à échoué"; 

                                        $this->send($user->socket,json_encode($message)); 
                                    }
                                    else
                                    {
                                        $tableau = pg_fetch_array($resultat,0, PGSQL_ASSOC);

                                        unset($message);
                                        $resultat_json = $tableau['json'];
                                        $message='{"requete":"mise_a_jour_resultat","statut":"ok","data":'.$resultat_json.'}';

                                        sleep(1);
                                        // envoi des nouveaux resultats à tout les clients
                                        foreach ($this->users as $utilisateur) {

                                            $this->send($utilisateur->socket,$message); 
                                        }
                                    }

                                }
                                break;
                                case "gla"    : $this->send($user->socket,"zup human");                        break;
                                case "name"  : $this->send($user->socket,"my name is Multivac, silly I know"); break;
                                case "age"   : $this->send($user->socket,"I am older than time itself");       break;
                                case "date"  : $this->send($user->socket,"today is ".date("Y.m.d"));           break;
                                case "time"  : $this->send($user->socket,"server time is ".date("H:i:s"));     break;
                                case "thanks": $this->send($user->socket,"you're welcome");                    break;
                                case "bye"   : $this->send($user->socket,"bye");                               break;
                                /*default      : 

                                    unset($message);
                                    $message['requete']=$requete;
                                    $message['statut']="echoue";
                                    $message['debug']="Requete malformée";

                                    $this->send($user->socket,json_encode($message));                       
                                break;*/
                            }

                        }
                    }

                    // deconnexion BD
                    pg_close($bd_connexion);
                }
            }
        }
    }
}

$master = new ChatBot("0.0.0.0",12345);
