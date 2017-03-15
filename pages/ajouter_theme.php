<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Ajout thème</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Ajout d'un thème :</h2>
  <?php //Vérification de la réception de l'ensemble des données, si il manque une variable
  if(!(isset($_POST["theme"]) AND isset($_POST["descriptif"]))) {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='ajout_theme.php'>Ajouter un autre thème ?</a></p>";
  } else {
    //Récupération des variables du formulaire
    $theme = htmlspecialchars($_POST["theme"], ENT_QUOTES);
    $descriptif = htmlspecialchars($_POST["descriptif"], ENT_QUOTES);

    //Connection à la BDD
    include 'secret.php';
    $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql');

    //Recherche du thème dans la base (pour savoir s'il existe déjà)
    $result=mysqli_query($connect, "SELECT idtheme, supprime FROM themes WHERE nom = '$theme'");
    if ($result) {
      //S'il n'existe pas déjà
      if(mysqli_num_rows($result)==0 ) {
        //Ajout du thème à la table
        $result=mysqli_query($connect, "INSERT INTO themes VALUES (NULL, '$theme', 0, '$descriptif')");
        if(!$result) {
          echo "<p class='warn'>Un problème est survenu lors de l'ajout du thème.<br/>Contactez l'administrateur du site.</p>";
          echo "Erreur : ".mysqli_error($connect);
        }
        else {
          echo "<p>Le thème <b>$theme</b> a bien été ajouté !</br>";
        }
      } else { //S'il existe déjà
        $row = mysqli_fetch_array($result);
        //Si le thème est déjà activé
        if($row[1]==0) {
          echo "<p class='warn'>Impossible : le thème <b>$theme</b> existe déjà !</p>";
        } else { //Si le thème est désactivé, on l'active !
          $result = mysqli_query($connect, "UPDATE themes SET supprime = 0 WHERE idtheme = $row[0]");
          if(!$result) {
            echo "<p class='warn'>Impossible de réactiver le thème.<br/>Contactez l'administrateur du site.</p>";
            echo "Erreur : ".mysqli_error($connect);
          } else {
            echo "<p class='ok'>Le thème <b>$theme</b> a été ré-activé car il avait déjà précédemment existé !</br>";
          }
        }
      }
    } else {
      echo "<p class='warn'>Impossible de déterminer si le thème existe déjà.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    mysqli_close($connect);
  }
  echo "<p><a href='ajout_theme.html'>Ajouter un autre thème ?</a> <a href='ajout_seance.php'>Ajouter une séance ?</a></p>"; ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
