<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Ajout séance</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
  <h2>Séances en attente de notation :</h2>
  <p class="tip">Cliquez sur la séance que vous désirez noter.</p>
	<?php //Récupération de la date et de l'heure
  date_default_timezone_set('Europe/Paris');
  $date = date("Y\-m\-d"); $heure = date("H:i:s");
  setlocale(LC_TIME, 'fr_FR.UTF8');
  //Connexion à la BDD et requête
  $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  //Requête : séances non notées antérieures à la date/heure du jour rangées de la plus ancienne à la plus récente
  $query = "SELECT * FROM seances INNER JOIN themes
            ON seances.id_theme = themes.idtheme
            WHERE note=0
            AND (date<'$date' OR (date='$date' AND heure<'$heure'))
            AND nb_inscrits != 0
            ORDER BY date, heure";
  $result = mysqli_query($connect, $query);
  if($result) {
    //Si aucune séance à noter
    if(mysqli_num_rows($result)==0) {
      echo "<p class='warn'>Aucune séance n'est en attente de notation.</p>
      <p><a href='inscription_eleve.php'>Inscrire un élève</a> <a href='ajout_seance.php'>Ajouter une séance</a></p>";
    } else {
      echo "<form method='POST' action='valider_seance.php' target='noteEtu'>
        <table id='matab'>
          <tr>
            <th>Thème</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Nombre d'inscrits</th>
          </tr>";
          $i=0;
          while($row = mysqli_fetch_array($result)) { $i+=1;
          $joliedate = strftime('%d %B %Y (%A)',strtotime($row[2]));
          $jolieheure = date("H\hi", strtotime($row[3]));
          echo "<tr Onclick='ligne($i)'>
            <td>$row[7]<input name='idseance' value='$row[0]' class='tab' type='submit'/></td>
            <td>$joliedate<input name='idseance' value='$row[0]' class='tab' type='submit'/></td>
            <td>$jolieheure<input name='idseance' value='$row[0]' class='tab' type='submit'/></td>
            <td>$row[4]<input name='idseance' value='$row[0]' class='tab' type='submit'/></td>
          </tr>";
        }
        echo "</table>
      </form>";
    }
  } else {
    echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
    echo "Erreur : ".mysqli_error($connect);
  }
  mysqli_close($connect); ?>
  <iframe id="noteEtu" name="noteEtu"></iframe>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  function sizeInit() { $('iframe#noteEtu').css("height", "0px"); }
  function montrerPage() { $('iframe#noteEtu').fadeIn(); }
  function cacherPage() { $('iframe#noteEtu').hide(); }
  function iframeLoad() { $('iframe#noteEtu').css("height", $('iframe#noteEtu').contents().height()); }
  //Pour le petit trait orange lorsqu'une ligne est sélectionnée...
  var nblignes = document.getElementById('matab').getElementsByTagName('tr').length;
  function ligne(nb) {
    for (var i=1; i<nblignes; i++) {
      if(i==nb) {
        document.getElementById('matab').getElementsByTagName('tr')[i].className = "trSelected";
      } else {
        document.getElementById('matab').getElementsByTagName('tr')[i].className = "";
      }
    }
  }
  </script>
</body>
</html>
