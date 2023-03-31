<?php
ob_start();

$commande = true;
include_once("header.php");
include_once("main.php");

$query1 = "select idclient from client";
$objstmt = $pdo->prepare($query1);
$objstmt->execute();

$query2 = "select idarticle from article";
$objstmt2 = $pdo->prepare($query2);
$objstmt2->execute();

$query3 = "select quantite from ligne_commande where idcommande";
$objstmt3 = $pdo->prepare($query3);
$objstmt3->execute();

if(!empty($_POST)){
    $query = "update commande set idclient=:clt, date=:dt where idcommande=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["clt"=>$_POST["inputClt"],"dt"=>$_POST["inputdate"],"id"=>$_POST["myid"]]);
    $pdostmt->closeCursor();

    $query2 = "update ligne_commande set idarticle=:art, quantite=:qte where idcommande=:id";
    $pdostmt2 = $pdo->prepare($query2);
    $pdostmt2->execute(["art"=>$_POST["inputarticle"],"qte"=>$_POST["inputqte"],"id"=>$_POST["myid"]]);
    $pdostmt2->closeCursor();
    header("Location:commande.php");
}


if(!empty($_GET["id"])){
    $query = "select * from commande, ligne_commande where commande.idcommande=ligne_commande.idcommande and commande.idcommande=:idCmd";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["idCmd"=>$_GET["id"]]);
    while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)):
?>

<h1 class="mt-5">Modifier une commande</h1>

<form class="row g-3" method="POST">
  <input type="hidden" name="myid" value="<?php echo $ligne["idcommande"]; ?>" />

  <div class="col-md-6">
    <label for="inputClt" class="form-label">idClient</label>
    <select name="inputClt" class="form-select" required>
      <?php
      foreach($objstmt->fetchAll(PDO::FETCH_ASSOC) as $tab){
        foreach($tab as $elmnt){
            if($ligne["idclient"] == $elmnt)
                echo "<option value=".$elmnt." selected".">".$elmnt."</option>";
            else
                echo "<option value=".$elmnt.">".$elmnt."</option>";
        }
      }
      ?>
    </select>
  </div>

  <div class="col-md-6">
    <label for="inputdate" class="form-label">Date</label>
    <input type="date" class="form-control" id="inputdate" name="inputdate" value="<?php echo $ligne["date"]; ?>" required>
  </div>

  <div class="col-md-6">
    <label for="inputarticle" class="form-label">idArticle</label>
    <select name="inputarticle" class="form-select" required>
      <?php
      foreach($objstmt2->fetchAll(PDO::FETCH_ASSOC) as $tab){
        foreach($tab as $elmnt){
            if($ligne["idarticle"] == $elmnt)
                echo "<option value=".$elmnt." selected".">".$elmnt."</option>";
            else
                echo "<option value=".$elmnt.">".$elmnt."</option>";
        }
      }
      ?>
    </select>
  </div>

  <div class="col-md-6">
    <label for="inputqte" class="form-label">Quantite</label>
    <input type="text" class="form-control" id="inputqte" name="inputqte" value="<?php echo $ligne["quantite"]; ?>" required>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Modifier</button>
  </div>
</form>

</div>
</main>

<?php
endwhile;
$pdostmt->closeCursor();

}

ob_end_flush();
?>

<?php
include_once("footer.php");
?>