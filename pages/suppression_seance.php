<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Suppression séance</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
  <p class="tip">Cliquez sur le bouton "Supprimer" d'une séance pour supprimer celle-ci. Attention, cette action est irréversible.</p>
  <!-- Si une suppression a été annulée on affiche un message de confirmation -->
  <?php if(isset($_POST['eta']) AND $_POST['eta']="NON" ) {
    echo "<p class='ok'>Suppression correctement annulée</p>";
  } ?>
  <form method="POST" action="suppresser_seance.php">
		<?php //On récupère la date et l'heure
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
    $date = date("Y\-m\-d"); $heure = date("H:i:s");

    //Connexion à la BDD et requête : on récupère les séances futures
    $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    $query = "SELECT ID, nom, date, heure, nb_inscrits
              FROM seances INNER JOIN themes
              ON seances.id_theme = themes.idtheme
              WHERE date>'$date' OR (date='$date' AND heure>'$heure')
              ORDER BY date, heure";
    $result = mysqli_query($connect, $query);
    if($result) {
      if(mysqli_num_rows($result)==0) {
        echo "<p class='warn'>Aucune séance n'est à venir.</p>
        <p><a href='ajout_seance.php'>Ajouter une séance</a></p>";
      } else {
        echo "<div class='jstest'>
            <input id='recherche'  class='maj' placeholder='Tapez ici pour rechercher' type='text'  autofocus='autofocus'>
            <p id='resultats'><b>Astuce : </b>Vous pouvez appuyer sur la touche entrée pour immédiatement consulter le premier résultat de votre recherche.</p>
            <p id='erreur' class='warn'></p>
          </div>";
        echo "<table class='list'>
          <tr>
              <th>Thème</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Nombre d'inscrits</th>
              <th>Action</th>
            </tr>";
          while ($row = mysqli_fetch_array($result)) {
            $joliedate = strftime('%d %B %Y',strtotime($row[2]));
            $jolieheure = date("H\hi",strtotime($row[3]));
            echo "<tr class='list' style='font-size:0.8cm;'>
              <td>$row[1]</td>
              <td>$joliedate</td>
              <td>$jolieheure</td>
              <td>$row[4]/20</td>
              <td><input type='submit' name='$row[0]' style='top:-15px;' value='Supprimer'/></td>
            </tr>";
          }
        echo "</table>";
      }
    } else {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    }
    mysqli_close($connect); ?>
	</form>
  <script>
  $(document).ready(function() {
    //Montrer le champ de recherche
    $('.jstest').show();
    //Chaque fois qu'un caractère y est écrit, relancer une recherche
    $("#recherche").on("keyup", function() {
      //Récupération du texte recherché en majuscules
      var cherche = $(this).val().toUpperCase(); $i =0;
      $("table tr").each(function(index) {
        $ligne = $(this);
        //On récupère le nom et le prénom en majuscule
        var nom = $ligne.find("td:nth-child(1)").text().toUpperCase();
        var prenom = $ligne.find("td:nth-child(2)").text().toUpperCase();
        if ((nom.indexOf(cherche) == -1) && (prenom.indexOf(cherche) == -1)) {
          //Si le texte recherché ne fait pas partie de la ligne on la cache
          $ligne.hide();
        }
        else {
          //Sinon on la montre et on compte
          $ligne.show();
          $i+=1;
        }
      });
      //Affichage en fonction du nombre de résultats
      if($i==0) {
        $('#resultats').html("");
        $('#erreur').html("Aucune séance ne correspond à votre recherche !");
      } else {
        $('#erreur').html("");
        if($i==1) {
          $('#resultats').html($i+" séance seulement correspond à votre recherche.");
        } else {
          $('#resultats').html("Il y a "+$i+" séances qui correspondent à votre recherche.");
        }
      }
    });
    parent.montrerPage();
  });
  </script>
</body>
</html>
