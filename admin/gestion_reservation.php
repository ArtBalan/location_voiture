<?php
include "../inc/init.inc.php";
include "../inc/function.inc.php";

// Redirige si l'utilisateur n'est pas admin
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // Permet de bloquer l'execution de la suite du code de la page.
}

//******************************//
// SUPRESSION DE LA RESERVATION //
//******************************//
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_reservation'])) {

    // $id_reservation = $_GET['id_reservation'];
    $supprimer = $pdo->prepare("DELETE FROM reservation WHERE id_reservation = :id_reservation");
    $supprimer->bindParam(':id_reservation', $_GET['id_reservation'], PDO::PARAM_STR);
    $supprimer->execute();

    if ($supprimer->rowCount() > 0) {
        $msg .= '<div class="alert alert-success">La réservation n° ' . $_GET['id_reservation'] . ' a bien été supprimée. </div>';
    }
}

$id_reservation = ''; // Champ caché du formulaire réservé à la modification
$id_membre = '';
$vehicule = '';
$nom = '';
$prenom = '';
$telephone = '';
$date_debut = '';
$date_fin = '';
$vehicule = '';
$permis = '';
$info = '';
$tarif = '';
$erreur = false ;

//******************************************//
// RECUPERATION DES DONNEES SI MODIFICATION //
//******************************************//
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_reservation'])) {
    $modification = $pdo->prepare("SELECT * FROM reservation WHERE id_reservation = :id_reservation");
    $modification->bindParam(':id_reservation', $_GET['id_reservation'], PDO::PARAM_STR);
    $modification->execute();

    if ($modification->rowCount() > 0) {
        $infos = $modification->fetch(PDO::FETCH_ASSOC);

        $id_reservation = $infos['id_reservation'];
        $id_membre = $infos['id_membre'];
        $vehicule = $infos['vehicule'];
        $nom = $infos['nom'];
        $prenom = $infos['prenom'];
        $telephone = $infos['telephone'];
        $date_debut = $infos['date_debut'];
        $date_fin = $infos['date_fin'];
        $vehicule = $infos['vehicule'];
        $permis = $infos['permis'];
        $info = $infos['info'];
        $tarif = $infos['tarif'];
    }
}

//***********************//
// Enregistrement en BDD //
//***********************//
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']) && isset($_POST['date_debut']) && isset($_POST['date_fin']) && isset($_POST['vehicule']) && isset($_POST['permis']) && isset($_POST['info']) && isset($_POST['tarif'])) {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $telephone = trim($_POST['telephone']);
    $date_debut = trim($_POST['date_debut']);
    $date_fin = trim($_POST['date_fin']);
    $vehicule = trim($_POST['vehicule']);
    $permis = trim($_POST['permis']);
    $info = trim($_POST['info']);
    $tarif = trim($_POST['tarif']);

    // Récupération de l'id si modification
    if (!empty($_POST['id_reservation'])) {
        $id_reservation = trim($_POST['id_reservation']);
    }

    //*************************************//
    // Enregistrement de la resa en bdd //
    //*************************************//
    if ($erreur == false) {
        if (empty($id_reservation)) {
            $enregistrement = $pdo->prepare("INSERT INTO reservation (id_reservation, nom, prenom, telephone, date_debut, date_fin, vehicule, permis, info, tarif) VALUES (NULL, :nom, :prenom, :telephone, :date_debut, :date_fin, :vehicule, :permis, :info, :tarif)");
        } else {
            $enregistrement = $pdo->prepare("UPDATE reservation SET nom = :nom, prenom = :prenom, telephone = :telephone, date_debut = :date_debut, date_fin = :date_fin, vehicule = :vehicule, permis = :permis, info = :info, tarif = :tarif  WHERE id_reservation = :id_reservation");
            $enregistrement->bindParam(':id_reservation', $id_reservation, PDO::PARAM_STR);
        }

        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
        $enregistrement->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
        $enregistrement->bindParam(':vehicule', $vehicule, PDO::PARAM_STR);
        $enregistrement->bindParam(':permis', $permis, PDO::PARAM_STR);
        $enregistrement->bindParam(':info', $info, PDO::PARAM_STR);
        $enregistrement->bindParam(':tarif', $tarif, PDO::PARAM_STR);
        $enregistrement->execute();

        // On redirige sur la même page afin de ne plus avoir la mémoire du formulaire si on recharge la page
        // header('location: gestion_reservation.php?msg=' . $enregistrement->errorInfo()[2]);
    }
}

//*******************************//
// Récupération des reservations //
//*******************************//
$resa = $pdo->query("SELECT * FROM reservation ORDER BY nom, prenom");


//***************************//
// Récupération des voitures //
//***************************//
$voitures = $pdo->query("SELECT * FROM voiture ORDER BY marque");

include "../inc/header.inc.php";
?>

<h1 class="text-center">Gestion réservation</h1>

<main class="container-fluid" style="padding-left: 0px; padding-right: 0px;">

    <div class="container">
        <div class="row mt-3">
            <div class="col-12"><?php echo $msg; ?></div>

            <div class="col-12">
                <form method="post" action="" class="p-3 row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <input type="hidden" name="id_reservation" value="<?= $id_reservation ?>">

                            <label for="nom"><i class="fas fa-user"></i> Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $nom; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="prenom"><i class="fas fa-user"></i> Prénom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo $prenom; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="telephone"><i class="fas fa-phone-square-alt"></i> Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" value="<?php echo $telephone; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="permis"><i class="fas fa-address-card"></i> Permis</label>
                            <input type="text" name="permis" id="permis" class="form-control" value="<?php echo $permis; ?>">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="date_debut"><i class="fas fa-calendar-week"></i> Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control" value="<?php echo $date_debut; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="date_fin"><i class="fas fa-calendar-week"></i> Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control" value="<?php echo $date_fin; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="vehicule"><i class="fas fa-car"></i> Véhicule</label>
                            <select name="vehicule" id="vehicule">
                                <?php
                                    // TABLE CONTENANT LA MARQUE ET MODEL DE CHAQUE VOITURE
                                    $listeVoiture = [];
                                    while($voiture = $voitures->fetch(PDO::FETCH_ASSOC)){
                                        $listeVoiture[$voiture['id']] = $voiture['marque'] . ' - ' . $voiture['modele'];
                                ?>
                                    <option value="<?= $voiture['id'] ?>"> <?= $voiture['marque'] ?> - <?= $voiture['modele']?>
                                <?php
                                    }
                                ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="info"><i class="fas fa-file-alt"></i> Info</label>
                            <input type="text" name="info" id="info" class="form-control" value="<?php echo $info; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tarif"><i class="fas fa-money-bill-alt"></i> Tarif</label>
                            <input type="text" name="tarif" id="tarif" class="form-control" value="<?php echo $tarif; ?>">
                        </div>

                        <div class="mb-3">
                            <button type="submit" id="enregistrement" class="btn btn-outline-dark w-100 rounded-pill"> Enregistrer<i class="fas fa-sign-in-alt"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="col-12 mt-5">
            <p class="alert alert-secondary">Il y a <?= $resa->rowCount() ?> réservation(s) enregistrée(s).</p>
            <hr>
            <table class="table table-bordered text-center w-100">
                <thead class="">
                    <tr>
                        <th><b>Id Réservation</b></th>
                        <th><b>Id Membre</b></th>
                        <th><b>Nom</b></th>
                        <th><b>Prénom</b></th>
                        <th><b>Téléphone</b></th>
                        <th><b>Permis</b></th>
                        <th><b>Date de début</b></th>
                        <th><b>Date de fin</b></th>
                        <th><b>Véhicule</b></th>
                        <th><b>Info</b></th>
                        <th><b>Tarif</b></th>
                        <th><b>Modif</b></th>
                        <th><b>Suppr</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($reservation = $resa->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $reservation['id_reservation'] . '</td>';
                        echo '<td>' . $reservation['id_membre'] . '</td>';
                        echo '<td>' . $reservation['nom'] . '</td>';
                        echo '<td>' . $reservation['prenom'] . '</td>';
                        echo '<td>' . $reservation['telephone'] . '</td>';
                        echo '<td>' . $reservation['permis'] . '</td>';
                        echo '<td>' . $reservation['date_debut'] . '</td>';
                        echo '<td>' . $reservation['date_fin'] . '</td>';
                        echo '<td>' . $listeVoiture[$reservation['vehicule']]. '</td>';
                        echo '<td>' . $reservation['info'] . '</td>';
                        echo '<td>' . $reservation['tarif'] . '</td>';

                        echo '<td><a href="?action=modifier&id_reservation=' . $reservation['id_reservation'] . '" class="btn btn-warning">editer</a></td>';
                        // AJOUTER UNE VALIDATION SUR LA SUPRESSION
                        echo '<td><a href="?action=supprimer&id_reservation=' . $reservation['id_reservation'] . '" class="btn btn-danger">suprimer</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



</main>


<?php
include "../inc/footer.inc.php";
