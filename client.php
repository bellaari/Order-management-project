<?php
$client = true;
include_once("header.php");
include_once("main.php");

$count = 0;
$list=[];

$query = "SELECT idclient FROM client WHERE idclient IN (SELECT c.idclient from client as c inner JOIN commande as cmd on c.idclient=cmd.idclient)";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
foreach($pdostmt->fetchAll(PDO::FETCH_ASSOC) as $tabvalues) {
    foreach($tabvalues as $tabelement){
        $list[] = $tabelement;
    }
}
?>

<!-- Begin page content -->

    <h1 class="mt-5">Clients</h1>
    <a href="addClient.php" class="btn btn-primary" style="float:right;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
        </svg>
    </a>

    <?php
        $query = "select * from client";
        $pdostmt = $pdo->prepare($query);
        $pdostmt->execute();
        // var_dump($pdostmt->fetchAll(PDO::FETCH_ASSOC));
    ?>

    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>idClient</th>
                <th>Nom</th>
                <th>Ville</th>
                <th>Telephone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): 
                $count++;
            ?>
            <tr>
                <td><?php echo $ligne["idclient"]; ?></td>
                <td><?php echo $ligne["nom"]; ?></td>
                <td><?php echo $ligne["ville"]; ?></td>
                <td><?php echo $ligne["telephone"]; ?></td>
                <td>
                    <a href="modifClient.php?id=<?php echo $ligne["idclient"] ?>" class="btn btn-success">
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