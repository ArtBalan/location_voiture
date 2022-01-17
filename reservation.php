<?php

include "inc/init.inc.php";
include "inc/function.inc.php";


if(isset($_GET['id_voiture'])){
    $requete = $pdo->prepare("SELECT * FROM voiture WHERE id_voiture = :id_voiture");
    $requete->bindParam(':id_voiture', $_GET['id_voiture'], PDO::PARAM_STR);
    $requete->execute();
} else {
    header('location:' . URL . 'voiture.php');
    exit();
}
include_once "inc/header.inc.php";
?>

<?php
if ($requete->rowCount() > 0){
    $infos = $requete->fetch(PDO::FETCH_ASSOC);
} else {
    $msg .= '<div class="alert alert-danger mb-3 ">⚠ Le véhicule en question n\'a pas été trouvé.</div>';
}
?>

    <main class="container-fluid ">
    <div class="bg-light p-5 text-center">
        <h1><i class="fas fa-tshirt"></i> <?= $infos['marque']; ?> <i class="fas fa-tshirt"></i></h1>
    </div>
    <div class="container">
        <div class="row mt-5">
            <!-- Affichage des messages utilisateurs -->
            <div class="col-12"><?= $msg; ?></div>
            <div class="col-md-6">

                <ul class="list-group ">
                    <li class="list-group-item bg-indigo rounded mb-3">Détails du véhicule </li>
                    <li class="list-group-item rounded mb-3 "><b>Marque : </b><?= $infos['marque']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Modèle : </b><?= $infos['modele']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Tarif 24 heures : </b><?= $infos['tarif24']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Tarif 48 heures : </b><?= $infos['tarif48']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Tarif hébdomadaire : </b><?= $infos['tarifSemaine']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Caution : </b><?= $infos['caution']; ?></li>
                </ul>

            </div>
            <div class="col-md-6">
                <img src="<?= URL . 'img/'. $infos['photo']; ?>" alt="">
            </div>
        </div>
    </div>
</main>





    <h1>Fiche de revervation</h1>
        
        <form action="" method="post">
            <div class="c100">
                <label for="Nom">Nom: </label>
                <input type="text" id="Nom" name="Nom">
            </div>

            <div class="c100">
                <label for="Prenom">Prenom</label>
                <input type="text" id="Prenom" name="Prenom">
            </div>

            <div class="c100">
                <label for="ville">Ville : </label>
                <input type="ville" id="vile" name="ville">
                 </div>


            
             <div class="c100">
            <label for="code_postal">code_postal:</label>
            <input type="text" id="code_postal" name="code_postal">
            </div>

            
            <div class="c100">
                <label for="Permis">Numero de permis: </label>
                <input  id="Permis" name="Permis">
            </div>

            <div class="c100">
                     <label for="Voiture">Voiture</label>
                     <input type="Voiture" name="Voiture">
                     </div>

                     <div class="c100">
                         <label for="Date">Date</label>
                         <input type="Date" name="Date">
                        </div>

                 <div class="c100" id="submit">
                <input type="submit" value="Reservation">
            </div>



            

                 


            

            
                
        </form>

        <?php
           include_once "inc/footer.inc.php";
        ?>
