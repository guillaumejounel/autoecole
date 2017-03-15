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
  <p class="warn" id="pbdate"></p>
  <!-- Si annulation d'un ajout on affiche une confirmation -->
  <?php if(isset($_POST['eta']) AND $_POST['eta']=="ANNULER") {
    echo "<p class='ok'>L'ajout de la séance a bien été annulé.</p>";
  } ?>
	<form id="form" method="POST" action="ajouter_seance.php">

    <!-- Liste des thèmes à sélectionner -->
		<label>Thème :
  		<?php
      date_default_timezone_set('Europe/Paris');
      //Connexion à la BDD et requête : obtenir tous les thèmes non supprimés
      include 'secret.php';
      $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
      $result = mysqli_query($connect, "SELECT idtheme, nom FROM themes WHERE supprime = 0 ORDER BY nom");
      if($result) {
        if(mysqli_num_rows($result)==0) {
          echo "<p class='warn'>Il n'existe aucun thème.</p>
          <p><a href='ajout_theme.html'>Ajouter un thème</a></p>";
        } else {
          echo "<select name='menuChoixTheme' size='3' required>";
          while ($row = mysqli_fetch_array($result)) {
            echo "<option value='$row[0]'";
            //Si le thème a déjà été choisi auparavant, on le sélectionne
            if(isset($_GET['idtheme']) AND $_GET['idtheme']==$row[0]) {
              echo "selected";
            }
            echo ">$row[1]</option>";
          }
          echo "</select>";
        }
      } else {
        echo "<p class='warn'>Un problème est survenu dans la récupération des données.<br/>Contactez l'administrateur du site.</p>";
        echo "Erreur : ".mysqli_error($connect);
      }
      mysqli_close($connect); ?>
    </label>

    <!-- Sélection de la date : pattern regex -->
		<label>Date de la séance :<br/>
        <input class="check temps" pattern="(30|31)|((1|2)[0-9])|(0?[1-9])" id="jour" type="text" name="jour" placeholder="J J" required/>
        <input class="check temps" pattern="(1[0-2])|(0?[1-9])" id="mois" type="text" name="mois" value="<?php echo date("m"); ?>" placeholder="MM" required/>
        <input class="check temps annee" pattern="(19|20)([0-9]{2})$" id="annee" type="text" name="annee" value="<?php echo date("Y"); ?>" placeholder="AAAA" required/>
    </label>
    <!-- Sélection de l'heure : pattern regex -->
		<label>Heure de la séance :<br/>
      <input class="check temps heure" pattern="(2[0-3])|(1[0-9])|(0?[0-9])" id="heure" type="text" name="heure" placeholder="HH" required/>:
      <input class="check temps heure" pattern="([1-5][0-9])|(0?[0-9])" id="minute" type="text" name="minute" placeholder="MM" required/>
    </label>
		<input type="submit" name="eta" value="Enregistrer séance"/>
	</form>
  <script>
  $(document).ready(function() {
    //Selectionner tout le texte des champs check lorsque l'on clique dessus
    $("input[type='text'].check").click(function () {
      $(this).select();
    });
    var limit = 0;
    //Lors de chaque keyup dans le champ check
    $('input[type=text].check').on('keyup', function(e) {
      //Déterminer la limite en taille du champ
      if(this.attributes["name"].value == "annee") { limit = 4; } else { limit = 2; }
      if (e.which != 9 && e.which !=16) { //Sauf si c'est la touche shift ou tab
        if (this.value.length >= limit) { //Si la taille est plus grande ou égale à la limite
          $(this).val(($(this).val()).substring(0, limit)); //Tronquer le champ à la limite
          $(this).next().focus(); //Sélectionner le champ suivant
        }
      }
    });
    //Lors de l'envoi du formulaire
    $("#form").submit(function() {
      $minute = parseInt($('#minute').val());
      $heure = parseInt($('#heure').val());
      $jour = parseInt($('#jour').val());
      $mois = parseInt($('#mois').val());
      $annee = parseInt($('#annee').val());
      $bonformat = true;
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
      if (!$bonformat) {
        $('#pbdate').html("La date que vous avez indiquée n'existe pas !");
        return false; //Ne pas envoyer le formulaire
      } else {
        $A = new Date().getFullYear(); $M = new Date().getMonth()+1;
        $J = new Date().getDate(); $H = new Date().getHours(); $m = new Date().getMinutes();
        if(($annee<$A)||(($annee==$A)&&(($mois<$M)||(($mois==$M)&&(($jour<$J)||(($jour==$J)&&(($heure<$H)||(($heure==$H)&&($minute<$m))))))))) {
          $('#pbdate').html("La date est déjà passée ! Nous sommes le "+$J+"/"+$M+"/"+$A+" et il est "+$H+"h"+$m+".");
          return false; //Ne pas envoyer le formulaire
        } else {
          $('#pbdate').html("");
        }
      }
    });
    parent.montrerPage();
  });
  </script>
</body>
</html>
