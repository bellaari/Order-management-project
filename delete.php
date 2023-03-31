<?php
include_once("main.php");

if(!empty($_GET["idClt"])) {
    $query = "DELETE FROM client WHERE idclient=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["idClt"]]);
    $pdostmt->closeCursor();

    header("Location:client.php");
}

if(!empty($_GET["idArt"])) {
    $query1 = "DELETE FROM image WHERE idarticle=:id";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute(["id"=>$_GET["idArt"]]);
    $pdostmt1->closeCursor();

    $query = "DELETE FROM article WHERE idarticle=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["idArt"]]);
    $pdostmt->closeCursor();

    header("Location:article.php");
}

if(!empty($_GET["idCmd"])){
    $query = "DELETE FROM ligne_commande WHERE idcommande=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["idCmd"]]);
    $pdostmt->closeCursor();

    $query2 = "DELETE FROM commande WHERE idcommande=:id";
    $pdostmt2 = $pdo->prepare($query2);
    $pdostmt2->execute(["id"=>$_GET["idCmd"]]);
    $pdostmt2->closeCursor();

    header("Location:commande.php");
}

if(!empty($_GET["idImg"]) && !empty($_GET["id_Art"])) {
    $query = "SELECT * FROM image WHERE idimg=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["idImg"]]);
    $row = $pdostmt->fetch(PDO::FETCH_ASSOC);
    unlink($row["cheminimg"]);
    $pdostmt->closeCursor();

    $query1 = "DELETE FROM image WHERE idimg=:id";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute(["id"=>$_GET["idImg"]]);
    $pdostmt1->closeCursor();

    header("Location:modifArticle.php?id=".$_GET["id_Art"]);
}

?>