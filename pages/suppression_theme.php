<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Suppression d'un thème</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Supprimer un thème :</h2>
  <p class="tip">Cliquez sur un thème pour supprimer celui-ci.</p>
  <!-- Si une suppression a été annulée on affiche un message de confirmation -->
  <?php if(isset($_POST['eta']) AND $_POST['eta']="NON" ) {
    echo "<p class='ok'>Suppression correctement annulée</p>";
  } ?>
	<?php //Connexion à la BDD et requête : thèmes non supprimés
        $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  $result = mysqli_query($connect, "SELECT * FROM themes WHERE supprime = 0 ORDER BY nom");
  if(!$result) {
    echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
    echo "Erreur : ".mysqli_error($connect);
  } else {
    //Si aucun thème ajouté
    if(mysqli_num_rows($result)==0) {
      echo "<p class='warn'>Aucun thème n'a été ajouté.</p>
      <p><a href='ajout_theme.html'>Ajouter un thème</a></p>";
    } else {
      echo "<form method='POST' action='supprimer_theme.php'>
        <div class='jstest'>
          <input id='recherche' placeholder='Tapez ici pour rechercher' type='text' autofocus='autofocus'>
          <p id='resultats'><b>Astuce : </b>Vous pouvez effectuer une recherche par intitulé ou par description pour trouver plus facilement le thème que vous désirez supprimer.</p>
          <p id='erreur' class='warn'></p>
        </div>
        <table class='list'>
        <tr>
          <th>Nom</th>
          <th>Description</th>
        </tr>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<tr class='list'>
            <td style='font-size:0.8cm;'>$row[1]<input name='idtheme' value='$row[0]' class='tab' type='submit'/></td>
            <td style='font-size:0.5cm;'>$row[3]<input name='idtheme' value='$row[0]' class='tab' type='submit'/></td>
          </tr>";
         }
         echo "</table>
         </form>";
      }
    }
  mysqli_close($connect); ?>
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
          $('#erreur').html("Aucun thème ne correspond à votre recherche !");
        } else {
          $('#erreur').html("");
          if($i==1) {
            $('#resultats').html($i+" thème seulement correspond à votre recherche.");
          } else {
            $('#resultats').html("Il y a "+$i+" thèmes qui correspondent à votre recherche.");
          }
        }
    });
    parent.montrerPage();
  });
  </script>
</body>
</html>
