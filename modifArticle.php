<?php
ob_start();

$article = true;
include_once("header.php");
include_once("main.php");

if(!empty($_POST) && !empty($_FILES)){
    $total_files = count($_FILES["inputimg"]["name"]);
    // var_dump($total_files);
    // die();

    $query = "update article set description=:desc, prix_unitaire=:pu where idarticle=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["desc"=>$_POST["inputdesc"],"pu"=>$_POST["inputpu"],"id"=>$_POST["myid"]]);
    $pdostmt->closeCursor();

    for($i = 0; $i<$total_files; $i++):
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
        $file = 'images/'.$uniqueName.".".$extension;
        //$file = 5f586bf96dcd38.73540086.jpg

        $query1 = "insert into image(nomimg,cheminimg,tailleimg,idarticle) values(:nom,:chemin,:taille,:id)";
        $pdostmt1 = $pdo->prepare($query1);
        $pdostmt1->execute(["nom"=>$name,"chemin"=>$file,"taille"=>$size,"id"=>$_POST["myid"]]);
        $pdostmt1->closeCursor();

        move_uploaded_file($tmpName, './'.$file);
        
        header("Location:article.php");
      }else{
        echo "Une erreur est survenue";
      }

    endfor;
}

if(!empty($_GET["id"])){
    $query = "select * from article where idarticle=:id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["id"=>$_GET["id"]]);
    $ligne = $pdostmt->fetch(PDO::FETCH_ASSOC);

    $query1 = "select * from image where idarticle=:id";
    $pdostmt1 = $pdo->prepare($query1);
    $pdostmt1->execute(["id"=>$_GET["id"]]);

?>

<h1 class="mt-5">Modifier un article</h1>

<form class="row g-3" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="myid" value="<?php echo $ligne["idarticle"]; ?>" />

  <div class="col-md-6">
    <label for="inputdesc" class="form-label">Description</label>
    <textarea class="form-control" id="inputdesc" name="inputdesc" required><?php echo $ligne["description"]; ?></textarea>
  </div>

  <div class="col-md-6">
    <label for="inputpu" class="form-label">PU</label>
    <input type="text" class="form-control" id="inputpu" name="inputpu" value="<?php echo $ligne["prix_unitaire"]; ?>" required>
  </div>

  <div class="col-md-7">
    <label for="inputimg" class="form-label">Image</label>
    <input type="file" class="form-control" id="inputimg" name="inputimg[]" multiple>
    <small>PNG, JPEG; JPG</small>
  </div>

  <div class="col-md-5">
    <?php while($row = $pdostmt1->fetch(PDO::FETCH_ASSOC)): ?>
      <a href="delete.php?idImg=<?php echo $row["idimg"] ?> & id_Art=<?php echo $row["idarticle"] ?>" class="btn btn-outline-danger" style="position:absolute">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
      </svg>
      </a>
      <img src="<?php echo $row["cheminimg"] ?>" height="100" width="100"/>
    <?php endwhile; ?>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Modifier</button>
  </div>
</form>

</div>
</main>

<?php

$pdostmt1->closeCursor();
$pdostmt->closeCursor();
}

ob_end_flush();
?>

<?php

include_once("footer.php");

?>