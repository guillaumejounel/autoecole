<?php //Si on annule l'ajout (en cas de doublon d'élève), on repropose l'ajout d'élève
if($_POST['eta']=='ANNULER') {
  include 'ajout_eleve.html';
} else { ?>
<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Ajout élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Ajout d'un élève :</h2>
	<?php //Vérification de la réception de l'ensemble des données, si il manque une variable
  if(!(isset($_POST["eta"]) AND isset($_POST["nom"]) AND isset($_POST["prenom"]) AND isset($_POST["jour"]) AND isset($_POST["mois"]) AND isset($_POST["annee"])) AND $_POST['eta']!='VALIDER') {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='ajout_eleve.html'>Ajouter un autre élève ?</a></p>";
  } else {
    //Récupération des variables du formulaire + date actuelle
  	date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
  	$date = date("Y\-m\-d");
    $nom = ucwords(htmlspecialchars($_POST["nom"], ENT_QUOTES));
    $prenom = ucwords(htmlspecialchars($_POST["prenom"], ENT_QUOTES));

    $bonformat=true;
    if($_POST['eta']!='VALIDER') {
      $jour = htmlspecialchars($_POST['jour'], ENT_QUOTES);
      $mois = htmlspecialchars($_POST['mois'], ENT_QUOTES);
      $annee = htmlspecialchars($_POST['annee'], ENT_QUOTES);
      $naissance = "$annee-$mois-$jour";
      //Vérification de la taille de la date & date non future
      if(strlen($jour)==0 OR strlen($jour)>2 OR strlen($mois)==0 OR strlen($mois)>2 OR strlen($annee)!=4 OR $naissance>$date) {
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
    } else {
      $naissance = htmlspecialchars($_POST['naissance']);
    }
    if (!$bonformat) {
      echo "<p class='warn'>La date de naissance que vous avez rentrée est invalide.</p>";
      echo "<p><a href='ajout_eleve.html'>Ajouter un autre élève ?</a></p>";
    } else {
      //Connection à la BDD
      $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql');
      //Recherche de l'élève dans la base (pour savoir s'il existe déjà)
      $result=mysqli_query($connect, "SELECT dateInscription FROM eleves WHERE nom = '$nom' AND prenom = '$prenom' LIMIT 1");
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        //S'il n'existe pas déjà ou si on a indiqué vouloir quand même l'enregistrer
        if(mysqli_num_rows($result)==0  OR $_POST['eta']=='VALIDER') {
          //On ajoute l'élève à la table
          $result=mysqli_query($connect, "INSERT INTO eleves VALUES (NULL, '$nom', '$prenom', '$naissance', '$date')");
          if(!$result) {
            echo "Erreur : ".mysqli_error($connect);
          } else {
            echo "<p><b class='maj'>$prenom $nom</b> a bien été ajouté !</br>";
            echo "<a href='ajout_eleve.html'>Ajouter un autre élève ?</a></p>";
          }
        } else { //Si l'élève existe déjà
          $row = mysqli_fetch_array($result);
          $datejolie = strftime('%d %B %Y',strtotime($row[0]));
          echo "<form method='POST'>Vous êtes sûrs ? <b class='maj'>$prenom $nom</b> a déjà été ajouté le <b>$datejolie</b> !<br/>
          <input type='hidden' name='nom' value='$nom'/>
        	<input type='hidden' name='prenom' value='$prenom'/>
        	<input type='hidden' name='naissance' value='$naissance'/>
          <input style='display:inline-block;' type='submit' name='eta' value='ANNULER'/>
          <input style='display:inline-block;' type='submit' name='eta' value='VALIDER'/>
          </form>";
        }
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
