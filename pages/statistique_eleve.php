<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Statistiques élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Statistiques d'un élève :</h2>
  <?php //Connexion à la BDD et requête
  include 'secret.php';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  if(isset($_GET['ideleve'])) {
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
    //Récupération de l'id de l'élève
    $ideleve = htmlspecialchars($_GET['ideleve'], ENT_QUOTES);

    $result = mysqli_query($connect, "SELECT nom, prenom, dateInscription FROM eleves WHERE ideleve = $ideleve");
    $row = mysqli_fetch_array($result);
    $nom = $row[0]; $prenom = $row[1]; $dateInscription = $row[2]; $dateinscrjolie = strftime('%d %B %Y',strtotime($dateInscription));

    $result = mysqli_query($connect, "SELECT COUNT(ideleve) FROM eleves WHERE dateInscription <= '$dateInscription'");
    $row = mysqli_fetch_array($result);

    echo "<p class='tip'>Statistiques de l'élève <b class='maj'>$nom $prenom</b> inscrit le <b>$dateinscrjolie</b>, <b>$row[0]<sup>e</sup></b> inscrit à l'auto-école.</p>";
    if(isset($_GET['idtheme'])) { $idtheme = htmlspecialchars($_GET['idtheme'], ENT_QUOTES); } else { $idtheme=0; } ?>

    <h3>Vos résultats
      <a href='?ideleve=<?php echo $ideleve; ?>'><span <?php if(!$idtheme) { echo "id='statcat'"; } ?>>Général</span></a>
      <?php
      $themes = array();
      $idthemes = array();
      $notes = array();
      $query = "SELECT idtheme, nom FROM themes
                WHERE EXISTS(
                  SELECT * FROM seances
                  INNER JOIN inscription
                  ON inscription.idseances = seances.ID
                  WHERE inscription.ideleve = $ideleve
                  AND themes.idtheme = seances.id_theme
                )
                ORDER BY idtheme";
      $result = mysqli_query($connect, $query);
      while ($row = mysqli_fetch_array($result)) {
        array_push($themes, $row[1]);
        array_push($idthemes, $row[0]);
        array_push($notes, "NULL");
        echo "<a href='?ideleve=$ideleve&idtheme=$row[0]'><span ";
        if($idtheme==$row[0]) { echo "id='statcat'"; }
        echo ">$row[1]</span></a>";
      }
      echo "</h3>";
      if(mysqli_num_rows($result)==0) {
        echo "<p class='warn'>L'élève n'a jamais participé à une séance.</p>
        <p><a href='inscription_eleve.php?eleve=$ideleve'>Inscrire l'élève</a></p>";
      } else {
    if($idtheme) {
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND ideleve = $ideleve AND nbfautes <= 5");
      $nb5 = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND ideleve = $ideleve AND nbfautes > 5 AND nbfautes <= 10");
      $nb10 = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND ideleve = $ideleve AND nbfautes > 10");
      $nb10p = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND ideleve = $ideleve");
      $nbseances = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT AVG(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND ideleve = $ideleve");
      $moyenne = mysqli_fetch_array($result)[0];
    } else {
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE ideleve = $ideleve AND nbfautes <= 5");
      $nb5 = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE ideleve = $ideleve AND nbfautes > 5 AND nbfautes <= 10");
      $nb10 = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE ideleve = $ideleve AND nbfautes > 10");
      $nb10p = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE ideleve = $ideleve");
      $nbseances = mysqli_fetch_array($result)[0];
      $result = mysqli_query($connect, "SELECT AVG(nbfautes) FROM inscription WHERE ideleve = $ideleve");
      $moyenne = mysqli_fetch_array($result)[0];
    }
    echo "
    <p>Nombre de fautes moyen : <b>".round($moyenne,1)."</b> (".round(($nb5/$nbseances)*100,1)."% de réussite)<br/>Nombre de tests réalisés : <b>$nbseances</b></p>";

    echo "<div class='item blockstat'><span>".round($moyenne,1)."</span><span class='descr'>fautes moyennes</span></div>
    <div class='item blockstat'><span>".round(($nb5/$nbseances)*100,1)."</span><span class='descr'>% de réussite</span></div>"; ?>
    <div id="donutchart" style="position:relative; top:-30px; width: 400px; height: 300px; margin-bottom:-100px;"></div>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Fautes', 'Nombre de fois'],
          ['Moins de 5',     <?php echo $nb5; ?>],
          ['Entre 5 et 10',      <?php echo $nb10; ?>],
          ['Plus de 10',  <?php echo $nb10p; ?>]
        ]);

        var options = {
          title: 'Répartition des notes',
          pieHole: 0.4,
          backgroundColor: { fill:'transparent' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>

    <?php echo "<h3>Votre évolution</h3>";

    //Liste des séances chronologique
    $query = "SELECT date, idtheme, nbfautes FROM `inscription` INNER JOIN seances
              ON seances.ID = inscription.idseances
              INNER JOIN themes
              ON themes.idtheme = seances.id_theme
              WHERE ideleve = $ideleve
              ORDER BY date, id_theme";
    $result = mysqli_query($connect,$query); ?>

    <script type="text/javascript"
            src="https://www.google.com/jsapi?autoload={
              'modules':[{
                'name':'visualization',
                'version':'1',
                'packages':['corechart']
              }]
            }"></script>

      <script type="text/javascript">
        google.setOnLoadCallback(drawChart);

        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Date'<?php
            for($i=0;$i<sizeof($themes);$i++) {
              echo ", '$themes[$i]'";
            } ?>]
            <?php
              //On balaye toutes les lignes
              $notemini = 40;
              $row = mysqli_fetch_array($result);
              while (isset($row[0])) {
                //On réinitialise le tableau des notes
                for($i=0;$i<sizeof($themes);$i++) {
                  $notes[$i]="null";
                }
                $madate = $row[0];
                //Tant que je suis à la même date
                while($row[0]==$madate) {
                  //Pour chaque thème
                  for($i=0;$i<sizeof($themes);$i++) {
                    //S'il y a une note on l'enregistre
                    if($idthemes[$i]==$row[1]) {
                      $notes[$i] = 40-$row[2];
                      if($notes[$i]<$notemini) {
                        $notemini = $notes[$i];
                      }
                    }
                  }
                  //On passe à la ligne suivante
                  $row = mysqli_fetch_array($result);
                }
                //On change de date, donc on affiche le résultat
                echo ", ['$madate'";
                for($i=0;$i<sizeof($themes);$i++) {
                  echo ", ".($notes[$i]);
                }
                echo "]";
              }
             ?>
          ]);

          var options = {
            title: 'Évolution du nombre de bonnes réponses par thème',
            curveType: 'function',
            legend: { position: 'bottom' },
            interpolateNulls:true,
            pointSize :20,
            lineWidth:7,
            vAxis:{
              viewWindowMode:'explicit',
              viewWindow: {
                min:0,
                max:45}
            }
          };

          var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

          chart.draw(data, options);
        }

      </script>
      <div id="curve_chart" style="width: 900px; height: 500px"></div>


  <?  }
  } else { ?>
    <?php //Consultation de tous les élèves
    $result = mysqli_query($connect, "SELECT ideleve, nom, prenom FROM eleves ORDER BY nom, prenom");
    if(!$result) {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    } else {
      //Si aucun élève inscrit
      if(mysqli_num_rows($result)==0) {
        echo "<p class='warn'>Aucun élève n'est inscrit à l'auto-école.</p>
        <p><a href='ajout_eleve.html'>Ajouter un élève</a></p>";
      } else {
        echo "<p class='tip'>Cliquez sur le nom d'un élève pour en afficher ses statistiques.</p>
          <div class='jstest'>
            <input id='recherche'  class='maj' placeholder='Tapez ici pour rechercher' type='text'  autofocus='autofocus'>
            <p id='resultats'><b>Astuce : </b>Vous pouvez appuyer sur la touche entrée pour immédiatement consulter le premier résultat de votre recherche.</p>
            <p id='erreur' class='warn'></p>
          </div>
          <ul>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<a href='?ideleve=$row[0]'><li class='list maj'>$row[2] $row[1]</li></a>";
        }
        echo "</ul>";
      }
    }
  }
  mysqli_close($connect); ?>
  <script type="text/javascript" src="../script/recherche.js"></script>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
