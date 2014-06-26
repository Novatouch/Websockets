$(document).ready(function() {



    if(!("WebSocket" in window)){
        //$('#chatLog, input, button, #examples').fadeOut("fast");
        $('<p>Oh no, you need a browser that supports WebSockets. How about <a href="http://www.google.com/chrome">Google Chrome</a>?</p>').appendTo('#container');
    }else{
        //The user has WebSockets




        function connect(){
            var socket;
            var id_utilisateur='<?php if(isset($_SESSION['id'])){ echo $_SESSION['id'];} ?>';
            var token_utilisateur='<?php if(isset($_SESSION['token'])){ echo $_SESSION['token'];} ?>';
   

            var host = "<?php echo $host_websocket; ?>";

            try{
                var socket = new WebSocket(host);


                //message('<p class="event">Socket Status: '+socket.readyState);

                socket.onopen = function(){
                    // message('<p class="event">Socket Status: '+socket.readyState+' (open)');

                    var info_requete = {};
                    info_requete.id=id_utilisateur;
                    info_requete.token=token_utilisateur;
                    info_requete.requete='lister_sondage_en_cours';

                    try{ socket.send(JSON.stringify(info_requete));  } catch(ex){ log(ex); }

                }

                socket.onmessage = function(msg){
                    // Ajout au journal du contenu du message

                    donnees=JSON.parse(msg.data);

                    // test le statut de la réponse
                    if(donnees.statut == "ok"){
                        // log("< requete connue recue");


                        // evaluation du type de requete
                        if(donnees.requete == "lister_sondage_en_cours"){

                            // mise à jour de la liste des sondage en cours

                            // vider la liste et l'affichage central
                            $("#choixReponse").empty();
                            $("#resultatSondage").empty();

                            for( var j=0 ; j < donnees.data.length; j++){

                                var id_sondage = donnees['data'][j].id;
                                var theme_sondage = donnees['data'][j].theme;
                            
                                if(j == 0){
                                    
                                    $("#liste_sondage").append('<a id=' + id_sondage +' href="#" class="list-group-item active">' + theme_sondage +'</a>');
                                } else {
                                    $("#liste_sondage").append('<a id=' + id_sondage +' href="#" class="list-group-item">' + theme_sondage +'</a>');
                                }

                            }
                        }else if(donnees.requete == "lister_question_reponse_sondage"){


                            $("#choixReponse").fadeOut( "slow" );
                            $("#resultatSondage").fadeOut( "slow" );

                            // recup
                            $("#choixReponse").empty();
                            $("#resultatSondage").empty();

                            // récupération infos sondages
                            var id_sondage = donnees['data']['sondage'][0].id;
                            var theme_sondage = donnees['data']['sondage'][0].theme;

                            


                            // récupération de la variable permettant de déterminer si l'utilisateur à déjà répondu au questionnaire
                            var participation = donnees['data']['participer'];

                            if(participation == "oui"){
                            
                                // affichage titre sondage
                                $("#resultatSondage").append('<h2 id=' + id_sondage +' >'+ theme_sondage +'</h2>');
                            
                            
                            
                                // affichage des résultats
                                

                                // parcours de la liste des questions
                                for( var i=0 ; i < donnees['data']['sondage'][0]['questions'].length; i++){

                                    // affichage des question
                                    var texte_question = donnees['data']['sondage'][0]['questions'][i]['ques_texte'];

                                    $("<h4>" + texte_question + "</h4>").appendTo( $("#resultatSondage") );

                                    for( var j=0 ; j < donnees['data']['sondage'][0]['questions'][i]['propositions'].length; j++){

                                        var texte_proposition = donnees['data']['sondage'][0]['questions'][i]['propositions'][j]['pro_texte'];    
                                        var nb_votants = donnees['data']['sondage'][0]['questions'][i]['propositions'][j]['pro_nombre_votants'];
                                        var pourcentage = donnees['data']['sondage'][0]['questions'][i]['propositions'][j]['score'];

                                        // affichage des propositions et des résultats
                                        $("<p>" + texte_proposition + "</p>").appendTo( $("#resultatSondage") );

                                        if(nb_votants > 1){
                                            var affichage_votants = 'votants';
                                        } else {
                                            var affichage_votants = 'votant';
                                        }
                                        $('<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="' + pourcentage + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + pourcentage + '%;">' + pourcentage + '% (' + nb_votants +''+ affichage_votants +')</div></div>').appendTo( $("#resultatSondage") );

                                    }
                                }
                                // affichage du div resultatSondage
                                $("#resultatSondage").fadeIn( "slow" );

                            }else if(participation == "non"){

                                // affichage titre sondage
                                $("#choixReponse").append('<h2 id=' + id_sondage +' >'+ theme_sondage +'</h2>');

                                // parcours de la liste des questions
                                for( var i=0 ; i < donnees['data']['sondage'][0]['questions'].length; i++){


                                    var texte_question = donnees['data']['sondage'][0]['questions'][i]['ques_texte'];
                                    var id_question = donnees['data']['sondage'][0]['questions'][i]['ques_id'];

                                    // affichage de la question
                                    $("<h4 id="+ id_question +" >" + texte_question + "</h4>").appendTo( $("#choixReponse") );

                                   // $("<p>").appendTo( $("#choixReponse") );

                                    for( var j=0 ; j < donnees['data']['sondage'][0]['questions'][i]['propositions'].length; j++){

                                        var texte_proposition = donnees['data']['sondage'][0]['questions'][i]['propositions'][j]['pro_texte'];
                                        var id_proposition = donnees['data']['sondage'][0]['questions'][i]['propositions'][j]['pro_id']; 

                                        // affichage des propositions
                                        $('<input id='+ id_proposition + ' type="radio" name="'+ id_question +'" /> <label>'+ texte_proposition +'</label><br />').appendTo( $("#choixReponse") );
                                    }

                                    //$("</p>").appendTo( $("#choixReponse") );
                                }
                                $('<br/>').appendTo( $("#choixReponse") );
                                $('<p><a class="btn btn-primary btn-lg" role="button" href="#">Soumettre</a></p>').appendTo( $("#choixReponse") );

                                // affichage du div resultatSondage
                                $("#choixReponse").fadeIn( "slow" );

                            } else {
                                log("données manquante dans la réponse serveur");
                            }


                        }else {
                            log("< requete non traité par la partie JS");
                        }



                    }else{
                        log("< requete inconnue recue");
                    }

                    //$('#liste_sondage .active').click();

                    // Génération d'un clic sur le premier élément de la liste
                }

                socket.onclose = function(){
                    message('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
                }        

            } catch(exception){
                message('<p>Error'+exception);
            }

            function send(text){

                text=JSON.stringify(text);
                try{
                    socket.send(text);
                    message('<p class="event">Sent: '+text)

                } catch(exception){
                    message('<p class="warning">');
                }
                $('#text').val("");
            }

            function message(msg){
                $('#choixReponse').append('<p>'+ msg + '</p>');
            }


            function log(txt) {
                $('#choixReponse').append('<p>'+ txt + '</p>');
            }

            $('#text').keypress(function(event) {
                if (event.keyCode == '13') {
                    send();
                }
            }); 

            // clic sur le lien de la liste des sondage
            $('#liste_sondage').on('click', 'a', function(e) { 
                e.preventDefault();

                // récupération de l'id du lien
                id_lien = $( this ).attr('id');

                // construction de la requete
                var info_requete = {};
                info_requete.id=id_utilisateur;
                info_requete.token=token_utilisateur;
                info_requete.requete='lister_question_reponse_sondage';
                info_requete.id_sondage=id_lien;       

                // envoi de la requête au serveur websocket
                try{ socket.send(JSON.stringify(info_requete));  } catch(ex){ log(ex); }

            });

            $('#disconnect').click(function(){
                socket.close();
            });

        }//End connect

        connect();
    }//End else

});
