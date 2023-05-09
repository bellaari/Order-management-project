<?php

include_once("connexion.php");
$pdo = new connect();

header("Access-Control-Allow-Origin: *");

// header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: *");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: *");

$query = "select * from article";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
$result = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
print_r(json_encode($result));

?>