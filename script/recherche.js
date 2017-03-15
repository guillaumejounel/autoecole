$(document).ready(function() {
  //Montrer le champ de recherche
  $('.jstest').show();
  //Chaque fois qu'un caractère y est écrit, relancer une recherche
  $("#recherche").on("keyup", function() {
    $i = 0; $firstline = "";
    //Récupération du texte recherché en majuscules
    var cherche = $(this).val().toUpperCase();
    //Pour chaque ligne (élève)
    $(".list").each(function(index) {
      $ligne = $(this);
      //On récupère le nom et le prénom en majuscule
      var nomprenom = $ligne.text().toUpperCase();
      if (nomprenom.indexOf(cherche) == -1) {
        //Si le texte recherché ne fait pas partie de la ligne on la cache
        $ligne.hide();
      } else {
        //Sinon on la montre et on compte
        $ligne.show();
        $i+=1; $firstline = $ligne;
      }
      if($i==1) {
        //Pour la première ligne on appelle la fct accesEntree
        accesEntree($firstline);
      }
    });
    //Permet d'envoyer une requête pour la 1ère ligne si appui sur entrée
    function accesEntree(item) {
      $('#recherche').keyup(function(e){
        //Si la touche keyup est entrée
        if(e.keyCode == 13)
        {
          //On accède au lien associé à la ligne
          document.location = item.parent().attr('href');
        }
      });
    }
    //Affichage en fonction du nombre de résultats
    if($i==0) {
      $('#resultats').html("");
      $('#erreur').html("Aucun élève ne correspond à votre recherche !");
    } else {
      $('#erreur').html("");
      if($i==1) {
        $('#resultats').html($i+" élève seulement correspond à votre recherche.");
      } else {
        $('#resultats').html("Il y a "+$i+" élèves qui correspondent à votre recherche.");
      }
    }
  });
});
