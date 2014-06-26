<?php include_once("contenu/config/config.php"); ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/ico/favicon.ico">
        

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
                <div id="pouet" class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="contenu/modules/deconnexion.php">STRISondages</a>

                </div> <!-- include menu> -->
                <?php
session_start();

if (isset($_SESSION['role']) && isset($_SESSION['id']) )
{
    if($_SESSION['role'] == "utilisateur")
    {
        include("contenu/interfaces/menu_utilisateur.php");
    }
    elseif($_SESSION['role'] == "administrateur")
    {
        include("contenu/interfaces/menu_administrateur.php");
    }
}
                ?>

            </div><!-- /.container -->
        </div><!-- /.navbar -->

        <div class="container">
            <?php
if (isset($_SESSION['role']) && isset($_SESSION['id']) )
{
    if($_SESSION['role'] == "utilisateur")
    {
        include("contenu/interfaces/contenu_utilisateur.php");
    }
    elseif($_SESSION['role'] == "administrateur")
    {
        include("contenu/interfaces/contenu_administrateur.php");
    }

}
else
{
    include("contenu/interfaces/contenu_authentification.php");
}
            ?>
            <br />
            <hr>
            <footer>
                <center><p>&copy; Philippe Gautier | Rémi Maison 2014</p></center>
            </footer>

        </div><!--/.container-->



        <!-- Bootstrap core JavaScript
================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!--<script src="js/offcanvas.js"></script>-->
        <script>
            <?php
            if (isset($_SESSION['role']) && isset($_SESSION['id']) )
            {
                if($_SESSION['role'] == "utilisateur")
                {
                   // include("contenu/interfaces/contenu_utilisateur.php");
                   include("contenu/templates/utilisateur.js.php");
                }
                elseif($_SESSION['role'] == "administrateur")
                {
                    //include("contenu/interfaces/contenu_administrateur.php");
                    include("contenu/templates/administrateur.js.php");
                }
            }
            else
            {
                include("contenu/templates/authentification.js.php");
            }
            ?>
        </script>
    </body>
</html>
