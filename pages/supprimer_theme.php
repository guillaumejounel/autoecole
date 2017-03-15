<?php if(isset($_POST['eta']) AND $_POST['eta']=="NON") { include 'suppression_theme.php'; } else { ?>
<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Supprimer un thème</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Supprimer un thème :</h2>
  <?php //Vérification de la transimission des données
  if(isset($_POST['idtheme'])) {
    //Récupération des données
    $idtheme = htmlspecialchars($_POST['idtheme'], ENT_QUOTES);
    //Si la confirmation a été faite
    if(isset($_POST['eta']) AND $_POST['eta']=="OUI") {
      //Connexion à la BDD : suppression du thème
      include 'secret.php';
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
      $result = mysqli_query($connect, "UPDATE themes SET supprime = 1 WHERE idtheme = $idtheme");
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu lors de la suppression du thème.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        echo "<p>Le thème a bien été supprimé !</br>";
        echo "<a href='suppression_theme.php'>Supprimer un autre thème ?</a></p>";
      }
      mysqli_close($connect);
    } else { //Si pas encore de confirmation
      echo "<form method='POST'>Confirmez-vous la suppression du thème ?</b><br/>
      <input type='hidden' name='idtheme' value='$idtheme'/>
      <input style='display:inline-block;' type='submit' name='eta' value='OUI'/>
      <input style='display:inline-block;' type='submit' name='eta' value='NON'/>
      </form>";
    }
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='supprimer_theme.php'>Supprimer un autre thème ?</a></p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
<?php } ?>
