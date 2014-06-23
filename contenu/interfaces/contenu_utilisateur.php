<!DOCTYPE html>
<html lang="fr">
  <head>
  </head>

  <body>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <div class="jumbotron">
            <h2>Sondage en cours</h2>
            <p></p>
          </div>
          <div class="row">
            <div class="col-6 col-sm-6 col-lg-12">
            <div id="choixReponse">
              <h3>Qui selon-vous va gagner la Coupe du monde 2014 ?</h3>
                <p>
                    <form>
                        <input type="radio" name="choix" /> <label for="bresil">Brésil</label><br />
                        <input type="radio" name="choix" /> <label for="italie">Italie</label><br />
                        <input type="radio" name="choix" /> <label for="france">France</label><br />
                        <input type="radio" name="choix" /> <label for="allemagne">Allemagne</label><br />
                        <input type="radio" name="choix" /> <label for="argentine">Argentine</label><br />
                        <input type="radio" name="choix" /> <label for="autre">Autre</label><br />
                    </form>
                </p>
              <p><a class="btn btn-primary btn-lg" role="button" href="#">Soumettre >></a></p>
              </div>
              <div id="resultatSondage">
				<p>
                    Brésil<div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="80" style="width: 30%">
                      	<span class="sr-only">30% Complete (success)</span>
                      </div>
                    </div>
                    Italie<div class="progress">
                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="80" style="width: 25%">
                        <span class="sr-only">25% Complete</span>
                      </div>
                    </div>
                    France<div class="progress">
                      <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="80" style="width: 10%">
                        <span class="sr-only">10% Complete (warning)</span>
                      </div>
                    </div>
                    Allemagne<div class="progress">
                      <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80" style="width: 29%">
                        <span class="sr-only">29% Complete</span>
                      </div>
                    </div>
                    Argentine<div class="progress">
                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80" style="width: 5%">
                        <span class="sr-only">5% Complete</span>
                      </div>
                    </div>
                    Autres<div class="progress">
                      <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80" style="width: 1%">
                        <span class="sr-only">1% Complete</span>
                      </div>
                </div>
                </p>
              </div>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a href="#" class="list-group-item active">Coupe du monde 2014</a>
            <a href="#" class="list-group-item">Informatique</a>
          </div>
        </div><!--/span-->
      </div><!--/row-->

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="offcanvas.js"></script>
  </body>
</html>
