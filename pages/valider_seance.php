<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Ajout séance</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage(); parent.sizeInit();</script>
</head>
<body class="fenetre">
  <h3>Notation de la séance</h3>
  <?php //Vérification de la transimission des données
  if(isset($_POST['idseance'])) {
    //Obtention de la date et de l'heure
    date_default_timezone_set('Europe/Paris');
    $date = date("Y\-m\-d"); $heure = date("H:i:s");
    setlocale(LC_TIME, 'fr_FR.UTF8');
    //Récupération des données
    $idseance = htmlspecialchars($_POST['idseance'], ENT_QUOTES);
    //Connexion à la BDD et requête
    include 'secret.php';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    //Requête : élèves ayant participé à la séance
    $query = "SELECT eleves.ideleve, nom, prenom, dateInscription, nbfautes
              FROM eleves INNER JOIN inscription
              ON inscription.ideleve = eleves.ideleve
              WHERE idseances='$idseance'";
    $result = mysqli_query($connect, $query);
    if($result) {
      //On récupère le nombre d'élèves
      $nbeleves = mysqli_num_rows($result);
      if ($nbeleves == 0) {
        echo "<p class='warn'>Aucun élève n'est inscrit à la séance sélectionnée !</p>";
      } else {
        echo "<p><i>$nbeleves élèves ont participé à cette séance.</i></p>
        <form action='noter_eleves.php' method='POST'>
          <table id='matab'>
            <tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Inscrit depuis...</th>
              <th>Nombre de fautes</th>
            </tr>";
            while($row = mysqli_fetch_array($result)) {
              $date1 = new DateTime(date("Y\-m\-d"));
              $date2 = new DateTime($row[3]);
              $anciennete = $date1->diff($date2);
              echo "<tr Onclick='menu($row[0])'>
                <td>$row[1]</td>
                <td>$row[2]</td>
                <td>$anciennete->d jours</td>
                <td><input type='number' id='$row[0]' name='$row[0]' value='$row[4]' min='0' max='40'/>/40</td>
              </tr>";
            }
          echo "</table>
          <input type='hidden' name='idseance' value='$idseance'/>
          <input type='submit' value='VALIDER LES RÉSULTATS'/>
        </form>";
      }
    } else {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    mysqli_close($connect);
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
    parent.iframeLoad();
    function menu(id) {
      document.getElementById(id).select();
    }
    //Tronquer le champ de la note à 2 caractères
    $('input[type=number]').on('keyup', function(e) {
      if (this.value.length >= 2) {
        $(this).val(($(this).val()).substring(0, 2));
      }
    });
  });
  </script>
</body>
</html>
