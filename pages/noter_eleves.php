<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Noter élèves</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
  <h3>Enregistrement des résultats</h3>
  <?php //Vérification de la transmission des données
  if(isset($_POST['idseance'])) {
    //Récupération des données
    $idseance = htmlspecialchars($_POST['idseance'], ENT_QUOTES);
    //Connexion à la BDD
    include 'secret.php';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    //Mise à jour de chaque note pour chaque élève
    foreach ($_POST as $ideleve => $nbfautes) {
      if ($ideleve != "idseance") {
        $nbfautes = htmlspecialchars($nbfautes, ENT_QUOTES);
        $ideleve = htmlspecialchars($ideleve, ENT_QUOTES);
        $result = mysqli_query($connect, "UPDATE inscription SET nbfautes = '$nbfautes' WHERE idseances = '$idseance' AND ideleve = '$ideleve'");
        if(!$result) { echo "Erreur : ".mysqli_error($connect)."<br/>"; }
      }
    }
    //Marquage de la séance comme notée
    $result = mysqli_query($connect, "UPDATE seances SET note = 1 WHERE ID = '$idseance'");
    if($result) {
      echo "<p class='ok'>Résultats bien enregistrés</p>";
    } else {
      echo "<p class='warn'>Attention, la séance n'a pas pu être notée comme notée.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    mysqli_close($connect);
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
