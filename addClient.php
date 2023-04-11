<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<?php
$client = true;
include_once("header.php");
include_once("main.php");

$query2 = "SELECT departement_nom from departement order by departement_nom asc";
$pdostmt2 = $pdo->prepare($query2);
$pdostmt2->execute();

if(!empty($_POST["inputnom"]) && !empty($_POST["inputville"]) && !empty($_POST["inputtel"])){
    $query = "insert into client(nom,ville,telephone) values (:nom,:ville,:tel)";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["nom"=>$_POST["inputnom"],"ville"=>$_POST["inputville"],"tel"=>$_POST["inputtel"]]);
    $pdostmt->closeCursor();
    header("Location:client.php");

}

?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#inputdepart").on("change",function(){

      var depart_code = $("#inputdepart").val();

      if(depart_code){

        $.ajax({
          type: 'POST',
          url: 'ajaxData.php',
          data: 'depart_code='+depart_code,
          success: function(response){
            $("#inputville").html(response);
            // alert(response);
          }
        });

      }else{
        $("#inputville").html("<option value=''> Selectioner d'abord un departement </option>");
      }

    });
  })
</script>

<h1 class="mt-5">Ajouter un client</h1>

<form class="row g-3" method="POST">
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
            <option value="<?php echo $row["departement_code "] ?>"> <?php echo $row["departement_nom"] ?> </option>";
      <?php
        endwhile;
      ?>
    </select>
  </div>

  <div class="col-md-6">
    <label for="inputville" class="form-label">Ville</label>
    <select type="text" class="form-control" id="inputville" name="inputville" required>
        <option>Selectioner d'abord un departement</option>
    </select>
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