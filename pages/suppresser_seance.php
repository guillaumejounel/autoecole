<?php if(isset($_POST['eta']) AND $_POST['eta']=="NON") { include 'suppression_theme.php'; } else { ?>
<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Suppression séance</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Supprimer une séance :</h2>
  <?php //Vérification de la transimission des données
  if(isset($_POST)) {
    //Récupération des données
    $idseance = htmlspecialchars(key($_POST), ENT_QUOTES);
    //Si confirmation de la suppression
    if(isset($_POST['eta']) AND $_POST['eta']=="OUI") {
      //Connexion à la BDD et requête : supprimer la séance et toutes les inscriptions associées
      $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
      $query = "DELETE seances, inscription
                FROM seances
                INNER JOIN inscription
                ON seances.ID = inscription.idseances
                WHERE seances.ID = $idseance";
      $result = mysqli_query($connect, $query);
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu lors de la suppression de la séance.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        echo "<p>La séance a bien été supprimé !</br>";
        echo "<a href='suppression_seance.php'>Supprimer une autre séance ?</a></p>";
      }
      mysqli_close($connect);
    //Si la suppression n'a pas été confirmée
    } else {
      echo "<form method='POST'>Confirmez-vous la suppression de la séance ?</b><br/>
      <p class='tip'>Ceci entraînera la désinscription à cette séance de tous les élèves inscrits</p>
      <input type='hidden' name='$idseance'/>
      <input style='display:inline-block;' type='submit' name='eta' value='OUI'/>
      <input style='display:inline-block;' type='submit' name='eta' value='NON'/>
      </form>";
    }
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='suppression_seance.php'>Supprimer une autre séance ?</a></p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
<?php } ?>
