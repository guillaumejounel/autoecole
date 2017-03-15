<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Inscrire élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Inscription d'un élève :</h2>
  <?php //Vérification de la transimission des données
  if(isset($_POST['eleve']) AND isset($_POST["seance"])) {
    //Récupération des données
    $eleve = htmlspecialchars($_POST["eleve"], ENT_QUOTES);
    $seance = htmlspecialchars($_POST["seance"], ENT_QUOTES);

    //Connexion à la BDD et requête : on recherche les inscription de l'élève pour cette séance
    $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
    $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql');
    $result = mysqli_query($connect, "SELECT * FROM inscription WHERE idseances='$seance' AND ideleve='$eleve'");

    if($result) {
      //Si l'élève n'est pas déjà inscrit à la séance
      if(mysqli_num_rows($result)==0) {
        //On ajoute un élève au nombre d'inscrits de la séance et on inscrit l'élève
        $result = mysqli_query($connect, "INSERT INTO inscription VALUES ('$seance', '$eleve', 0)");
        if($result) {
          $result = mysqli_query($connect, "UPDATE seances SET nb_inscrits = nb_inscrits + 1 WHERE ID = '$seance'");
          if($result) {
            echo "<p class='ok'>Élève correctement inscrit à la séance.</p>";
          } else {
            echo "<p class='warn'>Attention, le nombre d'inscrits n'a pas pu être mis à jour !</p>";
            echo "Erreur : ".mysqli_error($connect);
          }
        } else {
          echo "<p class='warn'>Erreur lors de l'inscription de l'élève.</p>";
          echo "Erreur : ".mysqli_error($connect);
        }
      } else {
        echo "<p class='warn'>L'élève est déjà inscrit à la séance.</p>";
      }
    } else {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    mysqli_close($connect);
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
  }
  echo "<p><a href='inscription_eleve.php'>Inscrire un autre élève ?</a></p>";
	?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
