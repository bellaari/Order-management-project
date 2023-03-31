<?php
$index = true;
include_once("header.php");
include_once("main.php");

?>

<!-- Begin page content -->

    <h1 class="mt-5">Accueil</h1>

    <?php
        $query = "select * from client,commande,ligne_commande,article where client.idclient = commande.idclient and commande.idcommande = ligne_commande.idcommande and ligne_commande.idarticle = article.idarticle";
        $pdostmt = $pdo->prepare($query);
        $pdostmt->execute();
        // var_dump($pdostmt->fetchAll(PDO::FETCH_ASSOC));
    ?>

    <table id="myTable" class="display">
        <thead>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Ville</th>
                <th>Telephone</th>
                <th>Date</th>
                <th>Description</th>
                <th>Prix_Unitaire</th>
                <th>Quantite</th>
            </tr>
        </thead>
        <tbody>
            <?php while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): 
            ?>

            <tr>
                <td>
                  <a href="details.php?id=<?php echo $ligne["idcommande"] ?>" class="btn btn-outline-secondary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                  </svg>
                  </a>
                </td>
                <td><?php echo $ligne["nom"]; ?></td>
                <td><?php echo $ligne["ville"]; ?></td>
                <td><?php echo $ligne["telephone"]; ?></td>
                <td><?php echo $ligne["date"]; ?></td>
                <td><?php echo $ligne["description"]; ?></td>
                <td><?php echo $ligne["prix_unitaire"]; ?></td>
                <td><?php echo $ligne["quantite"]; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
  </div>
</main>


<?php
include_once("footer.php");
?>