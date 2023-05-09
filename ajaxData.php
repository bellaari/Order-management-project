<?php
// $client = true;

include_once("connexion.php");
$pdo = new connect();
// include_once("main.php");

    // $conn = mysqli_connect("localhost","root","","gestion_commande");

session_start();

$count = 0;
$list=[];

$query1 = "SELECT idclient FROM client WHERE idclient IN (SELECT c.idclient from client as c inner JOIN commande as cmd on c.idclient=cmd.idclient)";
$pdostmt1 = $pdo->prepare($query1);
$pdostmt1->execute();
foreach($pdostmt1->fetchAll(PDO::FETCH_ASSOC) as $tabvalues) {
    foreach($tabvalues as $tabelement){
        $list[] = $tabelement;
    }
}
$pdostmt1->closeCursor();


if(!empty($_POST["depart_code"])){
    $query = "SELECT * from villes_france where ville_departement=:code_departement order by ville_nom asc";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["code_departement"=>$_POST["depart_code"]]);

    while($row = $pdostmt->fetch(PDO::FETCH_ASSOC)):
        echo "<option value=".$row["ville_id"].">".$row["ville_nom"]."</option>";
    endwhile;

    $pdostmt->closeCursor();
}

echo json_encode($_POST);


if(!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])){
    $query = "insert into client(nom,ville_id,telephone) values (:nom,:ville,:tel)";
    $pdostmt = $pdo->prepare($query);
    $result = $pdostmt->execute(["nom"=>$_POST["inputnom"],"ville"=>$_POST["inputville"],"tel"=>$_POST["inputtel"]]);

    if($result){
        $response=[
            "value"=>true,
            "msg"=>"Ajout avec succes"
        ];
    }else{
        $response=[
            "value"=>false,
            "msg"=>$pdostmt->errorInfo()
        ];
    }

    echo json_encode($response);
    $pdostmt->closeCursor();
}


if(!empty($_GET["client"])){

    $query = "select * from client as c,villes_france as v,departement as d
    where c.ville_id=v.ville_id and v.ville_departement=d.departement_code";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute();

    while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): 
        $count++;
    ?>
    <tr>
        <td><?php echo $ligne["idclient"]; ?></td>
        <td><?php echo $ligne["nom"]; ?></td>
        <td><?php echo $ligne["departement_nom"]; ?></td>
        <td><?php echo $ligne["ville_nom"]; ?></td>
        <td><?php echo $ligne["telephone"]; ?></td>
        <td>
            <a onclick=update_client(<?php echo $ligne["idclient"]?>) class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
            </svg>
            </a>

            <button type="button"class="btn btn-danger" <?php if(in_array($ligne["idclient"],$list)) {echo "disabled";} ?> data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $count ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
            </svg>
            </button>
        </td>
    </tr>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal<?php echo $count ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Voulez vous vraiment supprimer ce client ?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
            <a href="delete.php?idClt=<?php echo $ligne["idclient"] ?>" class="btn btn-danger">Supprimer</a>
        </div>
        </div>
    </div>
    </div>

    <?php endwhile; 
    $pdostmt->closeCursor();

}

if(!empty($_GET["updated_client"])){
    $query = "select * from client where idclient=:id";
    $pdostmt= $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["updated_client"]]);
    $result = $pdostmt->fetch(PDO::FETCH_ASSOC);

    if($result)
    {
        $response = [
            "status" => true,
            "data" => $result
        ];
    }else{
        $response = [
            "status" => false,
            "data" => $pdostmt->errorInfo()
        ];
    }

    echo json_encode($response);

}

if(!empty($_POST["username_register"]) && !empty($_POST["email_register"]) && !empty($_POST["password_register"])) {

    $query2="select username from user where username=:user";
    $pdostmt2 = $pdo->prepare($query2);
    $pdostmt2->execute(["user"=>$_POST["username_register"]]);
    $result2 = $pdostmt2->fetch(PDO::FETCH_ASSOC);

    if(!$result2){
        $query1="select email from user where email=:mail";
        $pdostmt1 = $pdo->prepare($query1);
        $pdostmt1->execute(["mail"=>$_POST["email_register"]]);
        $result1 = $pdostmt1->fetch(PDO::FETCH_ASSOC);
    
        if(!$result1){
            $query = "insert into user(username,email,password) values(:user, :email, :pass)";
            $pdostmt = $pdo->prepare($query);
            $result = $pdostmt->execute(["user"=>$_POST["username_register"], "email"=>$_POST["email_register"],"pass"=>password_hash($_POST["password_register"],PASSWORD_DEFAULT)]);
    
            $msg = "ajout avec succes !";
    
            if($result){
                $response=[
                    "msg" => $msg,
                    "value" => true
                ];
            }else{
                $response=[
                    "msg" => $pdostmt->errorInfo(),
                    "value" => false
                ];
            }
        }else {
            $msg = "email exist deja";
            $response=[
                "email" => $msg
            ];
        }    
    }else {
        $msg = "username exist deja";
            $response=[
                "user" => $msg
            ];
    }


    echo json_encode($response);

}

if(!empty($_POST["username_login"]) && !empty($_POST["password_login"])){

    $query = "select * from user where username=:user";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["user" => $_POST["username_login"]]);
    $result = $pdostmt->fetch(PDO::FETCH_ASSOC);

    $pass = password_verify($_POST["password_login"],$result["password"]);

    if($result) {
        if($pass){
            $msg = "authentification avec succes !";

            $response=[
                "user" => $msg,
                "value" => true
            ];
            $_SESSION["user"]=$result["username"];
            header('Location:index.php');
        }else{
            $msg = "mot de pass eronne !";

            $response=[
                "mdp" => $msg,
                "value" => false
            ];
        }

    }else {
        $response=[
            "user" => "ce user n'existe pas",

            "value" => false
        ];
    }

    echo json_encode($response);
}

?>



