<?php

include "inc/init.inc.php";
include "inc/function.inc.php";


//*********************************//
// Recupération donnée du véhicule //
//*********************************//
if(isset($_GET['id_voiture'])){
    $requeteInfoVoiture = $pdo->prepare("SELECT * FROM voiture WHERE id_voiture = :id_voiture");
    $requeteInfoVoiture->bindParam(':id_voiture', $_GET['id_voiture'], PDO::PARAM_STR);
    $requeteInfoVoiture->execute();
} else {
    header('location:' . URL . 'voiture.php');
    exit();
}

//******************//
//
//******************//

if(isset($_POST['id_voiture']) && isset($_POST['date_debut']) && isset($_POST['date_fin']) && isset($_POST['permis'])){

    $requeteInfoReservations = $pdo->prepare("SELECT * FROM reservation WHERE id_voiture = :id_voiture");
    $requeteInfoReservations->bindParam(':id_voiture', $_POST['id_voiture'], PDO::PARAM_STR);
    $requeteInfoReservations->execute();
    $error = false;

    $reserver = false;
    while (($reservation = $requeteInfoReservations->fetch(PDO::FETCH_ASSOC)) && !$reserver) {
        if(date_overlap($reservation['date_debut'],$reservation['date_fin'],$_POST['date_debut'],$_POST['date_fin'])){
            $reserver = true;
            $error = true;
        }
    }

   if(user_is_connected()){
       if(!$error){
           $id_membre = $_SESSION['membre']['id_membre'];
           $date_debut = $_POST['date_debut'];
           $date_fin = $_POST['date_fin'];
           $id_voiture = $_POST['id_voiture'];
           $permis = $_POST['permis'];

           $enregistrementReservation = $pdo->prepare("INSERT INTO reservation (id_voiture, id_membre, date_debut, date_fin, permis) VALUES(:id_voiture, :id_membre, :date_debut, :date_fin, :permis)");
           $enregistrementReservation->bindParam(":id_voiture", $id_voiture, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":date_debut", $date_debut, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":date_fin", $date_fin, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":permis", $permis, PDO::PARAM_STR);
           $enregistrementReservation->execute();
       }
   } else {
       $msg .= "veuillez vous connecté pour réserver";
   }

   if($reserver){
       $msg .= "le vehicule est déjà réservé sur ce crénaux";
   }
}
include_once "inc/header.inc.php";
?>
<?= $msg ?>
<?php
if ($requeteInfoVoiture->rowCount() > 0){
    $infos = $requeteInfoVoiture->fetch(PDO::FETCH_ASSOC);
    $id_voiture = $infos['id_voiture'];
} else {
    header('location:' . URL . 'voiture.php');
    exit();
}
?>

    <main class="container-fluid ">
    <div class="bg-light p-5 text-center">
        <h1><i class="fas fa-tshirt"></i> <?= $infos['marque']; ?> <i class="fas fa-tshirt"></i></h1>
    </div>
    <div class="container">
        <div class="row mt-5">
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

    <h1>Fiche de revervation</h1>
        
    <form action="" method="post">
        <input type="hidden" name="id_voiture" value="<?= $id_voiture ?>">
        <div class="c100">
            <label for="permis">Numero de permis: </label>
            <input  id="permis" name="permis">
        </div>

        <div class="c100">
            <label for="date_debut">Date de début de réservation : </label>
            <input type="date" name="date_debut">
        </div>

        <div class="c100">
            <label for="date_fin">Date de fin de réservation : </label>
            <input type="date" name="date_fin">
        </div>

        <div class="c100" id="submit">
            <input type="submit" value="Reservation">
        </div>
    </form>

</main>

<?php
    include_once "inc/footer.inc.php";
?>
