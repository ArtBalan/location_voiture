<?php

include "inc/init.inc.php";
include "inc/function.inc.php";


//*********************************//
// Recupération donnée du véhicule //
//*********************************//
if(isset($_GET['id'])){
    // SI ID PRESENT DANS URL
    $requeteInfoVoiture = $pdo->prepare("SELECT * FROM voiture WHERE id = :id");
    $requeteInfoVoiture->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
    $requeteInfoVoiture->execute();

    if ($requeteInfoVoiture->rowCount() > 0){
        // SI VOITURE TROUVER EN BDD
        $infos = $requeteInfoVoiture->fetch(PDO::FETCH_ASSOC);
        $id = $infos['id'];
    } else {
        // SI VOITURE NON TROUVER EN BDD
        header('location:' . URL . 'voiture.php');
        exit();
    }
} else {
    // SI PAS ID DANS URL
    header('location:' . URL . 'voiture.php');
    exit();
}

//****************************//
// ENREGISTREMENT RESA EN BDD //
//****************************//
if(isset($_POST['vehicule']) && isset($_POST['date_debut']) && isset($_POST['date_fin']) && isset($_POST['permis']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone'])){

    //**********************************************//
    // RECUPERATION DES RESERVATION LIE AU VEHICULE //
    //**********************************************//
    $requeteInfoReservations = $pdo->prepare("SELECT * FROM reservation WHERE vehicule = :vehicule");
    $requeteInfoReservations->bindParam(':vehicule', $_POST['vehicule'], PDO::PARAM_STR);
    $requeteInfoReservations->execute();
    $error = false;


    //************************************//
    // VERIFICATION DE LA DISPO DU CRENAU //
    //************************************//
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
           $vehicule= $_POST['vehicule'];
           $permis = $_POST['permis'];
           $nom = $_POST['nom'];
           $prenom = $_POST['prenom'];
           $telephone = $_POST['telephone'];


           // A FAIRE LE CALCULE DU TARIF
           $tarif = 24;
           $info = "";

           $enregistrementReservation = $pdo->prepare("INSERT INTO reservation (id_membre, date_debut, date_fin, permis, nom, prenom, telephone, vehicule, info, tarif) VALUES(:id_membre, :date_debut, :date_fin, :permis, :nom, :prenom, :telephone, :vehicule, :info, :tarif)");
           $enregistrementReservation->bindParam(":vehicule", $vehicule, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":date_debut", $date_debut, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":date_fin", $date_fin, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":permis", $permis, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":nom", $nom, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":prenom", $prenom, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":telephone", $telephone, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":tarif", $tarif, PDO::PARAM_STR);
           $enregistrementReservation->bindParam(":info", $info, PDO::PARAM_STR);
           $enregistrementReservation->execute();

           $msg .= $enregistrementReservation->errorInfo()[2];

       }
   } else {
       $msg .= "veuillez vous connecté pour réserver";
   }

   if($reserver){
       $msg .= "le vehicule est déjà réservé sur ce crénaux";
   }
}
//**************************//
// Récupération réservation //
//**************************//
// Récuperation de la date d'aujourd'hui
$date_courrante = date("Y-m-d");
//on requpère les date de début de de fin des reservations à venir. Ainsi que data_debut et data_fin qui serviron a être injecter dans les elements de la liste sour forme de data-atribute en html
$requeteReservations = $pdo->prepare("SELECT DATE_FORMAT(date_debut, '%d/%m/%Y') AS date_debut, DATE_FORMAT(date_fin, '%d/%m/%Y') as date_fin, DATE_FORMAT(date_debut, '%Y%m%d') as data_debut, DATE_FORMAT(date_fin, '%Y%m%d') as data_fin FROM reservation WHERE :vehicule = vehicule AND date_fin > :date_courrante");
$requeteReservations->bindParam(":vehicule", $_GET['id'], PDO::PARAM_STR);
$requeteReservations->bindParam(":date_courrante", $date_courrante, PDO::PARAM_STR);
$requeteReservations->execute();


include_once "inc/header.inc.php";
?>
<?= $msg ?>
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
                    <li class="list-group-item rounded mb-3 "><b>Tarif hébdomadaire : </b><?= $infos['tarifSemaine']; ?></li>
                    <li class="list-group-item rounded mb-3 "><b>Caution : </b><?= $infos['caution']; ?></li>
                </ul>

            </div>
            <div class="col-md-6">
                <img src="<?= URL . 'img/'. $infos['image']; ?>" alt="">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h1>Fiche de revervation</h1>
            <form action="" method="post">
                <input type="hidden" name="vehicule" value="<?= $id ?>">
                <div class="c100">
                    <label for="permis">Numero de permis: </label>
                    <input  id="permis" name="permis">
                </div>
                <div class="c100">
                    <label for="nom">Nom : </label>
                    <input  id="nom" name="nom">
                </div>
                <div class="c100">
                    <label for="prenom">Prenom : </label>
                    <input  id="prenom" name="prenom">
                </div>
                <div class="c100">
                    <label for="telephone">Telephone : </label>
                    <input  id="telephone" name="telephone">
                </div>
                <div class="c100">
                    <label for="date_debut">Date de début de réservation : </label>
                    <input type="date" name="date_debut" id="date_debut">
                </div>
                <div class="c100">
                    <label for="date_fin">Date de fin de réservation : </label>
                    <input type="date" name="date_fin" id="date_fin">
                </div>
                <div class="c100" id="submit">
                    <input type="submit" value="Reservation" id="btn_reservation">
                </div>
            </form>
        </div>
        <div class="col-sm-6 mt-1">
            <h4 class="mt-5"> Le véhicule est déjà réservé sur ces créneaux : </h4>
            <ul class="list-group">
            <?php
                while($reservation = $requeteReservations->fetch(PDO::FETCH_ASSOC)){
                    echo '<li class="list-group-item dateItem" data-debut="'.$reservation['data_debut'].'" data-fin="'. $reservation['data_fin'].'">
                            - du '. $reservation['date_debut'] .' au '. $reservation['date_fin'].'
                            </li>';
                }

            ?>
            </ul>
        </div>
    </div>
    </div>


</main>
<script src="js/dateValidation.js"></script>
<?php
    include_once "inc/footer.inc.php";
?>
