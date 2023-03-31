<?php
$article = true;

include_once("header.php");
include_once("main.php");


if(!empty($_FILES && !empty($_POST["inputdesc"]) && !empty($_POST["inputpu"]))){
    // var_dump($_FILES);
    $query = "insert into article(description,prix_unitaire) values(:desc,:pu)";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["desc"=>$_POST["inputdesc"],"pu"=>$_POST["inputpu"]]);
    $idart = $pdo->lastInsertId();
    $pdostmt->closeCursor();

    for($i = 0; $i < count($_FILES['inputimg']['name']); $i++){
      $tmpName = $_FILES['inputimg']['tmp_name'][$i];
      $name = $_FILES['inputimg']['name'][$i];
      $size = $_FILES['inputimg']['size'][$i];
      $error = $_FILES['inputimg']['error'][$i];

      $tabExtension = explode('.', $name);
      $extension = strtolower(end($tabExtension));
      //Tableau des extensions que l'on accepte
      $extensions = ['jpg', 'png', 'jpeg', 'gif'];

      $maxSize = 400000;

      if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
        $uniqueName = uniqid('', true);
        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
        $file = $uniqueName.".".$extension;
        //$file = 5f586bf96dcd38.73540086.jpg

        move_uploaded_file($tmpName, './images/'.$file);

        $query1 = "insert into image(nomimg,cheminimg,tailleimg,idarticle) values(:nom,:chemin,:taille,:idart)";
        $pdostmt1 = $pdo->prepare($query1);
        $pdostmt1->execute(["nom"=> $name,"chemin"=> $file,"taille"=> $size,"idart"=> $idart]);
        $pdostmt1->closeCursor();

        header('Location:article.php');
      } else{
        echo "Une erreur est survenue";
      }
  }
}

?>

<h1 class="mt-5">Ajouter un article</h1>

<form class="row g-3" method="POST" enctype="multipart/form-data">

  <div class="col-md-6">
    <label for="inputdesc" class="form-label">Description</label>
    <textarea class="form-control" placeholder="mettre la description" id="inputdesc" name="inputdesc" required></textarea>
  </div>

  <div class="col-md-6">
    <label for="inputpu" class="form-label">PU</label>
    <input type="text" class="form-control" id="inputpu" name="inputpu" required>
  </div>

  <div class="col-md-12">
    <label for="inputimg" class="form-label">Image</label>
    <input type="file" class="form-control" id="inputimg" name="inputimg[]" multiple required>
    <small>PNG, JPEG; JPG</small>
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