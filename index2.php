<?php

// include_once("connexion.php");
// $pdo = new connect();

header("Access-Control-Allow-Origin: *");

$conn = mysqli_connect("localhost","root","","gestion_commande");

// header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: *");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: *");

// $query = "insert into article(description,prix_unitaire)";
// $pdostmt = $pdo->prepare($query);
// $pdostmt->execute();
// $result = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
// print_r(json_encode($result));

$description = $_POST['description'];
$prix_unitaire = $_POST['prix_unitaire'];

$query = "insert into article(description,prix_unitaire) values('$description','$prix_unitaire');";
$res = mysqli_query($conn,$query);

?>