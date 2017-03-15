<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Désinscription séance</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Désinscription d'un élève à une séance :</h2>
  <?php //Vérification de la transimission des données
  if(isset($_GET['ideleve']) OR isset($_POST['ideleve'])) {
    //Obtention de la date et de l'heure
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
    $date = date("Y\-m\-d"); $heure = date("H:i:s");
    //Récupération des données
    if(isset($_POST['ideleve'])) {
      $ideleve = htmlspecialchars($_POST['ideleve'], ENT_QUOTES);
      end($_POST); $idseance = key($_POST);
    } elseif(isset($_GET['ideleve'])) {
      $ideleve = htmlspecialchars($_GET['ideleve'], ENT_QUOTES);
    }
    //Connexion à la BDD et requête : obtenir tous les noms/id des thèmes non supprimés"
    $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');

    if(isset($idseance)) {
      //Désinscription des élèves inscrits à la séance supprimée
      $result = mysqli_query($connect, "DELETE FROM inscription WHERE idseances = $idseance AND ideleve = $ideleve");
      if($result) {
        //On retire une place occupée à la séance
        $result = mysqli_query($connect, "UPDATE seances SET nb_inscrits = nb_inscrits - 1 WHERE ID = $idseance");
        if($result) {
          echo "<p class='ok'>Élève correctement désinscrit à la séance.</p>";
        } else {
          echo "<p class='warn'>Attention, le nombre d'inscrits n'a pas pu être mis à jour !</p>";
          echo "Erreur : ".mysqli_error($connect);
        }
      } else {
        echo "<p class='warn'>Erreur lors de la désinscription de l'élève.</p>";
        echo "Erreur : ".mysqli_error($connect);
      }
    }

    //On consulte les séances futures auxquelles l'élève est inscrit
    $query = "SELECT ID, nom, date, heure FROM seances
              INNER JOIN inscription ON seances.ID = inscription.idseances
              INNER JOIN themes ON seances.id_theme = themes.idtheme
              WHERE inscription.ideleve = $ideleve AND (date>'$date' OR (date='$date' AND heure>'$heure')) ORDER BY date, heure";
    $result = mysqli_query($connect, $query);
    if($result) {
      //On récupère le nombre de séances
      $nbseances = mysqli_num_rows($result);
      if($nbseances==0) {
        echo "<p class='warn'>Aucune séance n'est programmée pour cet élève !</p>";
      } else {
        if ($nbseances == 1) {
          echo "<p class='tip'>Cliquez sur la séance pour en désinscrire l'élève :</p>";
        } else {
          echo "<p class='tip'>Cliquez sur l'une des <b>$nbseances</b> séances de l'élève pour l'en désinscrire :</p>";
        }
        echo "<form method='POST'>
        <input type='hidden' name='ideleve' value='$ideleve'/>
        <table class='list'>
          <tr>
            <th>Thème</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Action</th>
          </tr>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<tr class='list' style='font-size:0.8cm;'><td>$row[1]</td><td>".strftime('%d %B %Y',strtotime($row[2]))."</td><td>".date("H\hi",strtotime($row[3]))."</td><td><input type='submit' name='$row[0]' style='top:-15px;' value='Désinscrire'/></td></tr>";
        }
        echo "</table>
        </form>";
      }
    } else {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    echo "<p><a href='desinscription_seance.php'>Désinscrire un autre élève à une séance ?</a>
    <a href='inscription_eleve.php?eleve=$ideleve'>Inscrire l'élève à une séance ?</a></p>";
    mysqli_close($connect);
  //Si pas de données reçues
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='desinscription_seance.php'>Désinscrire un autre élève à une séance ?</a></p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
