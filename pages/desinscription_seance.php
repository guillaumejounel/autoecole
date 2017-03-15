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
  <!-- METHODE GET (plus adaptée/accessible car ça évite de mettre des input un peu partout) -->
  <?php //Connexion à la BDD et requête : obtenir tous les noms des élèves"
  $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
  $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
  //Consultation de tous les élèves
  $result = mysqli_query($connect, "SELECT ideleve, nom, prenom FROM eleves ORDER BY nom, prenom");
  if(!$result) {
    echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
    echo "Erreur : ".mysqli_error($connect);
  } else {
    if(mysqli_num_rows($result)==0) {
      echo "<p class='warn'>Aucun élève n'est inscrit à l'auto-école.</p>
      <p><a href='ajout_eleve.html'>Ajouter un élève</a></p>";
    } else {
      echo "<p class='tip'>Cliquez sur le nom d'un élève pour en afficher les séances.</p>
        <div class='jstest'>
          <input id='recherche'  class='maj' placeholder='Tapez ici pour rechercher' type='text'  autofocus='autofocus'>
          <p id='resultats'><b>Astuce : </b>Vous pouvez appuyer sur la touche entrée pour immédiatement consulter le premier résultat de votre recherche.</p>
          <p id='erreur' class='warn'></p>
        </div>
        <ul>";
      while ($row = mysqli_fetch_array($result)) {
        echo "<a href='desinscrire_seance.php?ideleve=$row[0]'><li class='list maj'>$row[2] $row[1]</li></a>";
      }
      echo "</ul>";
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
