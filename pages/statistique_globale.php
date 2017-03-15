<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Statistiques globales</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Statistiques globales :</h2>

  <?php
  include 'secret.php';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  $result = mysqli_query($connect, "SELECT COUNT(ideleve) FROM eleves");
  $row = mysqli_fetch_array($result);
  echo "<p class='tip'>Statistiques générales des <b>$row[0]</b> élèves inscrits à l'auto-école.</p>";

  if(isset($_GET['idtheme'])) { $idtheme = htmlspecialchars($_GET['idtheme'], ENT_QUOTES); } else { $idtheme=0; } ?>

    <h3>Résultats
      <a href='statistique_globale.php'><span <?php if(!$idtheme) { echo "id='statcat'"; } ?>>Général</span></a>
      <?php
      $themes = array();
      $idthemes = array();
      $notes = array();
      $query = "SELECT idtheme, nom FROM themes
                WHERE EXISTS(
                  SELECT * FROM seances
                  INNER JOIN inscription
                  ON inscription.idseances = seances.ID
                  WHERE themes.idtheme = seances.id_theme
                )
                ORDER BY idtheme";
      $result = mysqli_query($connect, $query);
      while ($row = mysqli_fetch_array($result)) {
        array_push($themes, $row[1]);
        array_push($idthemes, $row[0]);
        array_push($notes, "NULL");
        echo "<a href='?idtheme=$row[0]'><span ";
        if($idtheme==$row[0]) { echo "id='statcat'"; }
        echo ">$row[1]</span></a>";
      } ?>
  </h3>
  <?php
  if($idtheme) {
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND nbfautes <= 5");
    $nb5 = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND nbfautes > 5 AND nbfautes <= 10");
    $nb10 = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme AND nbfautes > 10");
    $nb10p = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme");
    $nbseances = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT AVG(nbfautes) FROM inscription INNER JOIN seances ON inscription.idseances = seances.ID WHERE id_theme = $idtheme");
    $moyenne = mysqli_fetch_array($result)[0];
  } else {
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE nbfautes <= 5");
    $nb5 = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE nbfautes > 5 AND nbfautes <= 10");
    $nb10 = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription WHERE nbfautes > 10");
    $nb10p = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT COUNT(nbfautes) FROM inscription ");
    $nbseances = mysqli_fetch_array($result)[0];
    $result = mysqli_query($connect, "SELECT AVG(nbfautes) FROM inscription");
    $moyenne = mysqli_fetch_array($result)[0];
  }
  if($nbseances!=0) {

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

    <?php
      include 'secret.php';
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
      $query = "SELECT date FROM `inscription`
                INNER JOIN seances
                ON seances.ID = inscription.idseances
                ORDER BY date";
      $result = mysqli_query($connect,$query);
     ?>
     <h3>Fréquentation de l'auto-école</h3>
         <script type="text/javascript">
           google.load("visualization", "1.1", {packages:["calendar"]});
           google.setOnLoadCallback(drawChart);

        function drawChart() {
            var dataTable = new google.visualization.DataTable();
            dataTable.addColumn({ type: 'date', id: 'Date' });
            dataTable.addColumn({ type: 'number', id: 'Won/Loss' });
            dataTable.addRows([
              <?php
                //On balaye toutes les lignes
                $row = mysqli_fetch_array($result);
                while (isset($row[0])) {
                  $nbgens=0;
                  $madate = $row[0];
                  //Tant que je suis à la même date
                  while($row[0]==$madate) {
                    $nbgens +=1;
                    $row = mysqli_fetch_array($result);
                  }
                  //On change de date, donc on affiche le résultat
                  list($annee, $mois, $jour) = explode('-', $madate);
                  echo "[ new Date($annee, $mois-1, $jour), $nbgens ],";
                }
               ?>
             ]);

            var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

            var options = {
              title : 'Nombre d\'élèves présents chaque jour',
              height: 350,
              backgroundColor: { fill:'transparent' }
            };

            chart.draw(dataTable, options);
        }
         </script>
     <div id="calendar_basic" style="width: 100%; height: 350px; margin:auto;"></div>
    <?php } else {
      echo "<p class='warn'>Il n'y a encore aucune séance réalisée !</p>";
    } ?>
    <script>
    $(document).ready(function() {
      parent.montrerPage();
    });
    </script>
</body>
</html>
