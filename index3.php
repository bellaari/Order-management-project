<?php
header("Access-Control-Allow-Origin: *");

$conn = mysqli_connect("localhost","root","","gestion_commande");

// header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: *");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: *");

if(!empty($_GET["idArt"])) {

    $query = "DELETE FROM article WHERE idarticle=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["idArt"]]);
    // $pdostmt->closeCursor();
    $res = mysqli_query($conn,$query);

}

// print_r(json_encode($result));

?>