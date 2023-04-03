<?php
include_once("main.php");

if(!empty($_POST["depart_code"])){
    $query = "SELECT * from villes_france where ville_departement=:code_departement order by ville_nom asc";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["code_departement"=>$_POST["depart_code"]]);

    while($row = $pdostmt->fetch(PDO::FETCH_ASSOC)):
        echo "<option value=".$row["ville_id"].">".$row["ville_nom"]."</option>";
    endwhile;

    $pdostmt->closeCursor();
}

?>
