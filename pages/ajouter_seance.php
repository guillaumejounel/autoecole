<?php //Si on annule l'ajout (en cas de doublon d'élève), on repropose l'ajout d'élève
if($_POST['eta']=='ANNULER') {
  include 'ajout_seance.php';
} else { ?>
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
	<h2>Ajout d'une séance :</h2>
  <?php //Vérification de la réception de l'ensemble des données, si il manque une variable
  if(!(isset($_POST["menuChoixTheme"]) AND isset($_POST["jour"]) AND isset($_POST["mois"]) AND isset($_POST["annee"]) AND isset($_POST["heure"]) AND isset($_POST["minute"])) AND $_POST['eta']!='VALIDER') {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='ajout_seance.php'>Ajouter une autre séance ?</a></p>";
  } else {
    //Récupération des variables du formulaire + date et heure actuelles
    date_default_timezone_set('Europe/Paris');
  	$date = date("Y\-m\-d"); $heurenow = date("H:i:s");
  	$menuChoixTheme = htmlspecialchars($_POST['menuChoixTheme'], ENT_QUOTES);
    $bonformat=true;

    if($_POST['eta']!='VALIDER') {
      $jour = htmlspecialchars($_POST['jour'], ENT_QUOTES);
      $mois = htmlspecialchars($_POST['mois'], ENT_QUOTES);
      $annee = htmlspecialchars($_POST['annee'], ENT_QUOTES);
      $date_seance = "$annee-$mois-$jour";
      $heure = htmlspecialchars($_POST['heure'], ENT_QUOTES);
      $minute = htmlspecialchars($_POST['minute'], ENT_QUOTES);
    	$heure_seance = "$heure:$minute:00";

      //Vérification de la taille de la date
      if(strlen($jour)==0 OR strlen($jour)>2 OR strlen($mois)==0 OR strlen($mois)>2 OR strlen($annee)!=4) {
        $bonformat=false;
      }
      //Vérification de la validité de la date
      if (($mois == 4 || $mois == 6 || $mois == 9 || $mois == 11) && ($jour == 31)) {
        $bonformat=false;
      } else if ($mois == 2) {
        $bissextile = ((($annee % 4 == 0) && ($annee % 100 != 0)) || ($annee % 400 == 0));
        if ($bissextile && ($jour > 29)) {
          $bonformat=false;
        } else if (!$bissextile && ($jour > 28)) {
          $bonformat=false;
        }
      }
      //Vérification date future
      if ($date_seance < $date OR (($date_seance == $date) AND ($heure_seance < $heurenow))) {
        $bonformat=false;
      }
    } else {
      $date_seance = htmlspecialchars($_POST['date_seance'], ENT_QUOTES);
      $heure_seance = htmlspecialchars($_POST['heure_seance'], ENT_QUOTES);
    }
    if (!$bonformat) {
      echo "<p class='warn'>La date et heure que vous avez rentrées sont invalides.</p>";
      echo "<p><a href='ajout_seance.php'>Ajouter une autre séance ?</a></p>";
    } else {
      //Connection à la BDD
      $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql');
      //Recherche de la séance dans la base ce même jour (pour savoir si elle existe déjà)
      $result=mysqli_query($connect, "SELECT heure FROM seances WHERE date = '$date_seance' AND id_theme = '$menuChoixTheme'");
      if($result) {
        //Si elle n'existe pas déjà ou si on a indiqué vouloir quand même l'enregistrer
        if(mysqli_num_rows($result)==0  OR $_POST['eta']=='VALIDER') {
          //On ajoute la séance à la table
          $result=mysqli_query($connect, "INSERT INTO seances VALUES (NULL, '$menuChoixTheme', '$date_seance', '$heure_seance', 0, 0)");
          if(!$result) {
            echo "<p class='warn'>Un problème est survenu lors de l'ajout de la séance.<br/>Contactez l'administrateur du site.</p>";
            echo "Erreur : ".mysqli_error($connect);
          } else {
            echo "<p>La séance a bien été ajoutée !</br>";
            echo "<a href='ajout_seance.php'>Ajouter une autre séance ?</a> <a href='inscription_eleve.php'>Inscrire un élève ?</a></p>";
          }
        } else { //Si la séance existe déjà
          $row = mysqli_fetch_array($result);
          $heurejolie = date("H\hi", strtotime($row[0]));
          echo "<form method='POST'>Vous êtes sûrs ? Il y a déjà une séance sur ce thème ce jour-là à <b>$heurejolie</b> !<br/>
          <input type='hidden' name='menuChoixTheme' value='$menuChoixTheme'/>
          <input type='hidden' name='date_seance' value='$date_seance'/>
          <input type='hidden' name='heure_seance' value='$heure_seance'/>
          <input style='display:inline-block;' type='submit' name='eta' value='ANNULER'/>
          <input style='display:inline-block;' type='submit' name='eta' value='VALIDER'/>
          </form>";
        }
      } else {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      }
      mysqli_close($connect);
    }
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
<?php } ?>
