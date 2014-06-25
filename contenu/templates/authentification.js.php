$(document).ready(function(){

    var ws = null;

    //  Création d'un nouveau socket
    // (Pour Mozilla < 11 avec version préfixée)
    if('MozWebSocket' in window) {

      ws = new MozWebSocket("ws://192.168.42.38:12345");

    } else if('WebSocket' in window) {

      ws = new WebSocket("ws://192.168.42.38:12345");

    }

    if(typeof ws =='undefined') {
      alert("Ce navigateur ne supporte pas Web Sockets");
    }
                     
    // Lors d'un clic sur le bouton submit    
    $("#bouton_submit").click(function(){

        // récupération des valeurs identifiant et password
        var data = {};
        data.identifiant=$("#login").val();
        data.pass=$("#mdp").val();

       // $("#pouet").append('<li>pouet '+data.identifiant+''+ data.pass +'</li>');

        // envoi des données à la page d'authentification
        $.getJSON("contenu/modules/authentification.php",
                  data,
                  function(data2){
                      
                      var message = data2.message;
                      
                      if(message == "succes"){
                          // authentification réussie rechargement de la page
                          location.reload();
                          
                      }else{
                          
                          // affichage du message d'erreur
                          // $("#formulaireConnexion").append('<p>pouet '+data2.debug+'</p>');
                          $("#pouet").append('<li>pouet '+data2.debug+'</li>');
    
                      }
                  });
    });
});


