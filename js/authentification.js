$(document).ready(function(){

    // Lors d'un clic sur le bouton submit    
    $("#submit").click(function(){

        // récupération des valeurs identifiant et password
        var data = {};
        data.identifiant=$("#identifiant").val();
        data.pass=$("#pass").val();

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
                          $("#pouet").append('<li>pouet '+data2.debug+'</li>');
    
                      }
                  });
    });
}); 