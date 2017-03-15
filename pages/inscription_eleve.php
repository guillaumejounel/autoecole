<!DOCTYPE>
<html>
<head>
  <meta charset="utf-8">
  <title>Inscrire élève</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>parent.cacherPage()</script>
</head>
<body class="fenetre">
	<h2>Inscription d'un élève :</h2>
  <p class="tip">Sélectionnez un élève ainsi que la séance à laquelle vous désirez l'inscrire.</<p>

	<?php //Récupération de la date et de l'heure
  setlocale(LC_TIME, 'fr_FR.UTF8');
  date_default_timezone_set('Europe/Paris');
	$date = date("Y\-m\-d"); $heure = date("H:i:s");

  //Connexion à la BDD
  $dbhost = 'localhost'; $dbuser = 'root'; $dbpass = 'root'; $dbname = 'nf92a012';
  $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname) or die ('Error connecting to mysql'); ?>

  <!-- Si le thème est déjà choisi on change l'action du formulaire -->
	<form method="POST" <?php if(isset($_POST['theme'])) { echo 'action="inscrire_eleve.php"';}?>>

    <!-- Liste des élèves à sélectionner -->
		<label class="deroulant">Élève :
      <?php //On récupère la liste de tous les élèves
      $result = mysqli_query($connect, "SELECT ideleve, nom, prenom FROM eleves");
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        if(mysqli_num_rows($result)==0) {
          echo "<p class='warn'>Aucun élève n'est inscrit à l'auto-école.</p>
          <p><a href='ajout_eleve.html'>Ajouter un élève</a></p>";
        } else {
          echo "<select class='maj' name='eleve' required>";
          while ($row = mysqli_fetch_array($result)) {
    			  echo "<option value='$row[0]'";
            //Si l'elève a déjà été sélectionné auparavant on le sélectionne
            if((isset($_POST['eleve']) && $_POST['eleve']==$row[0]) OR (isset($_GET['eleve']) && $_GET['eleve']==$row[0])) {
              echo 'selected';
            }
            echo ">$row[2] $row[1]</option>";
    			}
          echo "</select>";
        }
      } ?>
    </label>

    <!-- Liste des thèmes à sélectionner -->
		<label class="deroulant">Thème :
      <?php //On récupère la liste de tous les thèmes non supprimés ou bien qui ont une séance future programmée
      $query = "SELECT idtheme, nom FROM themes
                WHERE supprime = 0
                OR (supprime = 1
                  AND EXISTS(
                    SELECT * FROM seances
                    WHERE themes.idtheme = seances.id_theme
                    AND (date>'$date' OR (date='$date' AND heure>'$heure'))
                  )
                )";
      $result = mysqli_query($connect, $query);
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        if(mysqli_num_rows($result)==0) {
          echo "<p class='warn'>Il n'existe aucun thème.</p>
          <p><a href='ajout_theme.html'>Ajouter un thème</a></p>";
        } else {
          echo "<select class='maj' name='theme'";
          //Si déjà choisi, on bloque tout changement
          if(isset($_POST['theme'])) {
            echo 'disabled ';
          }
          echo "required>";
          while ($row = mysqli_fetch_array($result)) {
    			     echo "<option value='$row[0]'";
               //Si déjà choisi, on le sélectionne
               if(isset($_POST['theme']) && $_POST['theme']==$row[0]) {
                 echo 'selected';
               }
               echo ">$row[1]</option>";
    			}
          echo "</select>";
        }
      } ?>
    </label>

		<?php //Si le thème n'est pas choisi
    if(!isset($_POST['theme'])) {
  		echo "<input type='submit' value='Rechercher des séances'/>";
    //Si le thème est choisi et l'élève aussi
    } elseif (isset($_POST['eleve'])) {
      $idtheme = htmlspecialchars($_POST["theme"], ENT_QUOTES);
      $ideleve= htmlspecialchars($_POST["eleve"], ENT_QUOTES);
      //On recherche les séances futures de ce thème avec des places libres
      $query = "SELECT * FROM seances
                WHERE id_theme = '$idtheme'
                AND (date>'$date' OR (date='$date' AND heure>'$heure'))
                AND nb_inscrits < 20
                ORDER BY date, heure";
      $result = mysqli_query($connect, $query);
      if(!$result) {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      } else {
        //On récupère le nombre de séances
        $nbseances = mysqli_num_rows($result);
        if ($nbseances != 0) {
      		echo "<label class='deroulant'>$nbseances séance(s) disponible(s) :
        		<select name='seance' required>";
            while ($row = mysqli_fetch_array($result)) {
        			echo "<option value='$row[0]'>".ucfirst(strftime('%A %d %B',strtotime($row[2])))." à ".date("H\hi",strtotime($row[3]))." ($row[4]/20 places)</option>";
        		}
        		echo "</select>
          </label>
          <input type='submit' value='Inscrire l&apos;élève à cette séance'/><br/>
      		<a href='inscription_eleve.php?eleve=$ideleve'><div class='bouton tiny'>Sélectionner un autre thème</div></a>
          <a href='ajout_seance.php?idtheme=$idtheme'><div class='bouton tiny'>Ajouter une séance à ce thème</div></a>";
        } else {
          echo "<p class='warn'>Aucune séance n'est disponible pour ce thème. Sélectionnez-en un autre ou créez des séances.</p>
      		<a href='inscription_eleve.php?eleve=$ideleve'><div class='bouton'>Sélectionner un autre thème</div></a>
          <a href='ajout_seance.php?idtheme=$idtheme'><div class='bouton'>Ajouter une séance à ce thème</div></a>";
        }
      }
		} else {
      echo "<p class='warn'>Un problème est survenu, l'id de l'élève n'a pu être récupéré.<br/>Contactez l'administrateur du site.</p>";
    }
    mysqli_close($connect); ?>
	</form>
  <script>
  $(document).ready(function() {
    parent.montrerPage();
  });
  </script>
</body>
</html>
