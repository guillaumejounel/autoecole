<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Consultation élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Consultation d'un élève :</h2>
	<?php //Vérification de la transimission des données
  if(isset($_GET['ideleve'])) {
    //Obtention de la date
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, 'fr_FR.UTF8');
    $date = date("Y\-m\-d");
    //Récupération des données
    $ideleve = htmlspecialchars($_GET['ideleve'], ENT_QUOTES);
    //Connexion à la BDD et requête : obtenir toutes les informations de l'élève
    include 'secret.php';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    $result = mysqli_query($connect, "SELECT * FROM eleves WHERE ideleve='$ideleve'");
    if(!$result) {
      echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
      echo "Erreur : ".mysqli_error($connect);
    } else {
      //Récupération de la première ligne
      $row = mysqli_fetch_array($result);
      //Calcul de l'âge et de l'ancienneté (entre date actuelle et date de naissance/d'inscription)
      $datenow = new DateTime(date("Y\-m\-d"));
      $datenaiss = new DateTime($row[3]); $age = $datenow->diff($datenaiss);
      $dateinscr = new DateTime($row[4]); $anciennete = $datenow->diff($dateinscr);
      $datenaissjolie = strftime('%d %B %Y',strtotime($row[3]));
      $dateinscrjolie = strftime('%d %B %Y',strtotime($row[4]));
      //Affichage des caractéristiques de l'élève
      echo "<p class='tip'>Caractéristiques de l'élève <span class='maj'>$row[1] $row[2]</span> (ID : <b>$row[0]</b>)</p>
      <ul class='list'>
        <li>Nom : <b class='maj'>$row[1]</b></li>
        <li>Prénom : <b class='maj'>$row[2]</b></li>
        <li>Date de naissance : <b>$datenaissjolie</b> ($age->y ans)</li>
        <li>Date d'inscription : <b>$dateinscrjolie</b> (il y a $anciennete->d jours)</li>
      </ul>
      <p><a href='consultation_eleve.php'>Consulter un autre élève ?</a><br/>
      <a href='inscription_eleve.php?eleve=$ideleve' class='bouton tiny'>Inscrire l'élève à une séance</a>
      <a href='visualiser_calendrier_eleve.php?ideleve=$ideleve' class='bouton tiny'>Voir le calendrier de l'élève</a></p>";
    }
    mysqli_close($connect);
  //Si pas d'ideleve reçu
  } else {
    echo "<p class='warn'>Un problème est survenu dans la transmission des données.<br/>Contactez l'administrateur du site.</p>";
    echo "<p><a href='consultation_eleve.php'>Consulter un autre élève ?</a></p>";
  } ?>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
