<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <script type="text/javascript" src="js/authentification.js"></script>

    <title>Sondages STRI | Connexion</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">STRISondages</a>
        </div>
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">
    <div class="jumbotron">
    	<div id="formulaireConnexion">
        	<center>
        	<h2>Authentification</h2>
        	<br />
        	<p class="lead">
            <form action="index.php" method="post">
            	<label for="login">Login :</label><input type="text" id="login" name="login" /><br /><br />
            	<label for="mdp">Mot de passe :</label><input type="password" id="mdp" name="mdp" /><br /><br />
                <input type="submit" value="Connexion" class="btn btn-lg btn-success" />
            </form>
        	</p>
        	<!--<p><a class="btn btn-lg btn-success" href="accueil.php" role="button">Connexion</a></p>-->
        	</center>
        </div>
	</div>
        <br />
        <div id="affichageUtilisateur">
		<?php
			if (isset($_POST['login']) && isset($_POST['mdp']) && $_POST['login'] == 'maison' && $_POST['mdp'] == 'maison')
			{
				include("contenu/interfaces/menu_utilisateur.php");
				include("contenu/interfaces/contenu_utilisateur.php");
			}
			else if (isset($_POST['login']) && isset($_POST['mdp']) && $_POST['login'] == 'admin' && $_POST['mdp'] == 'admin')
			{
				include("contenu/interfaces/menu_administrateur.php");
				include("contenu/interfaces/contenu_administrateur.php");
			}
		?>
		</div>
      <hr>

      <footer>
        <center><p>&copy; Philippe Gautier | Rémi Maison 2014</p></center>
      </footer>

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="offcanvas.js"></script>
  </body>
</html>
