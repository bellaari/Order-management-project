<?php
$commande = true;
include_once("header.php");
include_once("main.php");

if(!empty($_POST["inputClt"]) && !empty($_POST["inputDt"])){
    $query = "insert into commande(idclient,date) values(:clt,:date)";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["clt"=>$_POST["inputClt"],"date"=>$_POST["inputDt"]]);
    $idCmd = $pdo->lastInsertId();
    
    $query2 = "insert into ligne_commande(idarticle,idcommande,quantite) values(:art,:cmd,:qte)";
    $pdostmt2 = $pdo->prepare($query2);
    $pdostmt2->execute(["art"=>$_POST["inputarticle"],"cmd"=>$idCmd,"qte"=>$_POST["inputqte"]]);
    $pdostmt2->closeCursor();

    header("Location:commande.php");
}

$query2 = "select idarticle from article";
$objstmt2 = $pdo->prepare($query2);
$objstmt2->execute();

?>

<h1 class="mt-5">Ajouter une commande</h1>

<form class="row g-3" method="POST">
  <div class="col-md-6">
    <label for="inputClt" class="form-label">idClient</label>
    <select id="inputClt" name="inputClt" class="form-select">
    <?php
        $query = "select idclient from client";
        $pdostmt = $pdo->prepare($query);
        $pdostmt->execute();
        while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)):
    ?>
      <option><?php echo $ligne["idclient"]; ?></option>

      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-6">
    <label for="inputDt" class="form-label">Date</label>
    <input type="date" class="form-control" id="inputDt" name="inputDt">
  </div>

  <div class="col-md-6">
    <label for="inputarticle" class="form-label">idArticle</label>
    <select name="inputarticle" class="form-select" required>
      <?php
      foreach($objstmt2->fetchAll(PDO::FETCH_ASSOC) as $tab){
        foreach($tab as $elmnt){
            echo "<option>".$elmnt."</option>";
        }
      }
      ?>
    </select>
  </div>

  <div class="col-md-6">
    <label for="inputqte" class="form-label">Quantite</label>
    <input type="text" class="form-control" id="inputqte" name="inputqte" required>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Ajouter</button>
  </div>

</form>

</div>
</main>

<?php

include_once("footer.php");

?>