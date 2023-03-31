<?php
include_once("header.php");
include_once("main.php");



if(!empty($_GET["id"])){
    $query = "select * from client,commande,ligne_commande,article where client.idclient = commande.idclient and commande.idcommande = ligne_commande.idcommande and ligne_commande.idarticle = article.idarticle and commande.idcommande=:idCmd";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["idCmd"=>$_GET["id"]]);
    $ligne = $pdostmt->fetch(PDO::FETCH_ASSOC);

    $query_vues = "update commande set vues=:views where idcommande=:id";
    $objstmt = $pdo->prepare($query_vues);
    $objstmt->execute(["id"=>$ligne["idcommande"],"views"=>$ligne["vues"]+1]);

    $query_vues_select = "select * from commande where idcommande=:id";
    $objstmt_select = $pdo->prepare($query_vues_select);
    $objstmt_select->execute(["id"=>$ligne["idcommande"]]);
    $row = $objstmt_select->fetch(PDO::FETCH_ASSOC);
?>

<div style="float:right; color:blue;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
    </svg>
    <?php echo $row["vues"]; ?>
</div>

<h1 class="mt-5">Details de la commande</h1>

<form class="row g-3" method="POST">

  <div class="col-md-6">
    <label for="inputClt" class="form-label">idClient</label>
    <input type="text" class="form-control" id="inputClt" name="inputClt" value="<?php echo $ligne["idclient"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputnom" class="form-label">Nom</label>
    <input type="text" class="form-control" id="inputnom" name="inputnom" value="<?php echo $ligne["nom"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputdate" class="form-label">Date</label>
    <input type="date" class="form-control" id="inputdate" name="inputdate" value="<?php echo $ligne["date"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputarticle" class="form-label">idArticle</label>
    <input type="text" class="form-control" id="inputarticle" name="inputarticle" value="<?php echo $ligne["idarticle"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputqte" class="form-label">Quantite</label>
    <input type="text" class="form-control" id="inputqte" name="inputqte" value="<?php echo $ligne["quantite"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputville" class="form-label">Ville</label>
    <input type="text" class="form-control" id="inputville" name="inputville" value="<?php echo $ligne["ville"]; ?>" disabled>
  </div>

  <div class="col-md-6">
    <label for="inputtel" class="form-label">Telephone</label>
    <input type="text" class="form-control" id="inputtel" name="inputtel" value="<?php echo $ligne["telephone"]; ?>" disabled>
  </div>

  <div class="col-12">
    <a href="index.php" class="btn btn-primary">Fermer</a>
  </div>
</form>

</div>
</main>

<?php

$objstmt_select->closeCursor();
$objstmt->closeCursor();
$pdostmt->closeCursor();

}
?>

<?php
include_once("footer.php");
?>