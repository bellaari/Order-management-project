<?php
$commande = true;
include_once("header.php");
include_once("main.php");

$count = 0;
$list=[];

$query = "SELECT idcommande FROM commande WHERE idcommande IN (SELECT cmd.idcommande from commande as cmd inner JOIN ligne_commande as lc on cmd.idcommande=lc.idcommande)";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
foreach($pdostmt->fetchAll(PDO::FETCH_ASSOC) as $tabvalues) {
    foreach($tabvalues as $tabelement){
        $list[] = $tabelement;
    }
}

?>

<!-- Begin page content -->

    <h1 class="mt-5">Commandes</h1>

    <a href="addCommande.php" class="btn btn-primary" style="float:right; margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
    </a>

    <?php
        $query = "select * from commande";
        $pdostmt = $pdo->prepare($query);
        $pdostmt->execute();
    ?>

    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>idCommande</th>
                <th>idClient</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): 
                $count++;
        ?>
            <tr>
                <td><?php echo $ligne["idcommande"]; ?></td>
                <td><?php echo $ligne["idclient"]; ?></td>
                <td><?php echo $ligne["date"]; ?></td>
                <td>
                    <a href="modifCommande.php?id=<?php echo $ligne["idcommande"] ?>" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    </a>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" <?php if(in_array($ligne["idclient"],$list)) {echo "disabled";} ?> data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $count; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                    </svg>
                    </button>
                </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal<?php echo $count; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                Voulez vous vraiment supprimer cette commande ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="delete.php?idCmd=<?php echo $ligne["idcommande"]; ?>" class="btn btn-danger">Supprimer</a>
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