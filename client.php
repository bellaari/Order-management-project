<?php
$client = true;
include_once("header.php");
include_once("main.php");

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

if(!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])){
    $query1 = "insert into client(nom,ville,telephone) values (:nom,:ville,:tel)";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute(["nom"=>$_POST["inputnom"],"ville"=>$_POST["inputville"],"tel"=>$_POST["inputtel"]]);
    $pdostmt1->closeCursor();
    header("Location:client.php");     
}

$query2 = "SELECT * from departement order by departement_nom asc";
$pdostmt2 = $pdo->prepare($query2);
$pdostmt2->execute();

?>

<script >
    function update_client(id){
        // console.log(id);
        $.ajax({
            type: "GET",
            url: "ajaxData.php?updated_client="+id,
            dataType: "json",
            success: function(response){
                console.log(response);
                // $("#client_id").val(response.data.idclient);
                // $("inputnom").val(response.data.nom);
                // $("inputtel").val(response.data.telephone);
                $("#clientModal").modal("show");
            }
        })
    }

  $(document).ready(function(){
    $("#inputdepart").on("change",function(){

      var depart_code = $('#inputdepart').val();

      if(depart_code){

        $.ajax({
          type: 'POST',
          url: 'ajaxData.php',
          data: 'depart_code='+depart_code,
          success: function(response){
            $('#inputville').html(response);
            //alert(response);
          }
        });

      }else{
        $("#inputville").html("<option value=''>Selectioner d'abord un departement</option>");
      }
    });

    $("#clientform").submit(function(e){
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: 'ajaxData.php',
          data: $("#clientform").serialize(),
          dataType: "json",
          success: function(response){
            console.log(response);
            if(response.value){
                toastr.success(response.msg);
                $("#clientModal").modal("hide");
                $("#clientform")[0].reset();
                $("#inputville").val('');
            }else{
                toastr.error(response.msg);
            }
          },
          complete: function(){
            setInterval("location.reload()",1000);
          }
        });
    })

    // function getClients(){
    //     $.ajax({
    //         type: "GET",
    //         url: "ajaxData.php?client=true",
    //         dataType: "html",
    //         succes: function(response){
    //             console.log(response);
    //             $("#clients").html(response);
    //             // $('#myTable').DataTable();
    //         }
    //     })
    // };

    // getClients();

  })
</script>


<!-- Begin page content -->

    <h1 class="mt-5">Clients</h1>

    <button type="button" class="btn btn-primary" style="float:right;margin-bottom:20px;" data-bs-toggle="modal" data-bs-target="#clientModal" data-bs-whatever="@mdo">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
    </button>

    <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="lientModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="lientModal">Ajouter un client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="row g-3" method="POST" id="clientform">
            <div class="modal-body" >
                    <div class="col-md-6">
                        <label for="inputnom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="inputnom" name="inputnom" required>
                    </div>

                    <div class="col-md-6">
                        <label for="inputtel" class="form-label">Telephone</label>
                        <input type="tel" class="form-control" id="inputtel" name="inputtel" required>
                    </div>

                    <div class="col-md-6">
                        <label for="inputdepart" class="form-label">Departement</label>
                        <select type="text" class="form-control" id="inputdepart" name="inputdepart" required>
                        <option value="">Selectionner un departement</option>
                        <?php
                            while($row = $pdostmt2->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <option value="<?php echo $row["departement_code"] ?>"><?php echo $row["departement_nom"] ?></option>
                        <?php
                            endwhile;
                        ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="inputville" class="form-label">Ville</label>
                        <select type="text" class="form-control" id="inputville" name="inputville" >
                            <option value="">Selectionner d'abord un departement</option>
                        </select>
                    </div>

            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <!-- <a href="addClient.php" class="btn btn-primary" style="float:right; margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
    </a> -->

    <?php
            $query = "select * from client as c,villes_france as v,departement as d
            where c.ville_id=v.ville_id and v.ville_departement=d.departement_code";
            $pdostmt = $pdo->prepare($query);
            $pdostmt->execute();
        

    ?>

    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>idClient</th>
                <th>Nom</th>
                <th>Departement</th>
                <th>Ville</th>
                <th>Telephone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="clients">
     <?php   while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): 
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

    <?php endwhile; ?>

        </tbody>
    </table>
  </div>
</main>


<?php
include_once("footer.php");
?>