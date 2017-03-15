<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Calendrier élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Visualisation du calendrier d'un élève :</h2>
  <?php include 'secret.php';
  //Vérification de la transimission des données
  if(isset($_GET['ideleve'])) {
    //Obtention de la date
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
    $date = date("Y\-m\-d"); $heure = date("H:i:s");
    //Récupération des données
    $ideleve = htmlspecialchars($_GET['ideleve'], ENT_QUOTES);
    //Connexion à la BDD et requête : obtenir toutes les séance futures auxquelles l'élève est inscrit
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    $query = "SELECT nom, date, heure FROM seances
              INNER JOIN inscription ON seances.ID = inscription.idseances
              INNER JOIN themes ON seances.id_theme = themes.idtheme
              WHERE inscription.ideleve = $ideleve AND (date>'$date' OR (date='$date' AND heure>'$heure'))
              ORDER BY date, heure";
    $result = mysqli_query($connect, $query);
    if(!$result) {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    } else {
      //Récupération du nombre de séances
      $nbseances = mysqli_num_rows($result);
      if($nbseances==0) {
        echo "<p class='warn'>Aucune séance n'est programmée pour cet élève !</p>";
      } else {
        if ($nbseances == 1) {
          echo "<p class='tip'>Voici la prochaine séance de code à laquelle l'élève est inscrit :</p>";
        } else {
          echo "<p class='tip'>Voici les <b>$nbseances</b> prochaines séances de code auxquelles l'élève est inscrit :</p>";
        }
        echo "<table class='list'>
          <tr>
            <th>Thème</th>
            <th>Date</th>
            <th>Heure</th>
          </tr>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<tr class='list'><td>$row[0]</td><td>".strftime('%d %B %Y',strtotime($row[1]))."</td><td>".date("H\hi",strtotime($row[2]))."</td></tr>";
        }
        echo "</table>
        <p><a href='inscription_eleve.php?eleve=$ideleve'>Inscrire l'élève à une séance ?</a>
        <a href='visualisation_calendrier_eleve.php'>Consulter le planning d'un autre élève ?</a></p>";
      }
    }
    mysqli_close($connect);
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>
    <p><a href='visualisation_calendrier_eleve.php'>Consulter le planning d'un autre élève ?</a></p>";
  }
  ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
