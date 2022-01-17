<?php

include "../inc/init.inc.php";
include "../inc/function.inc.php";


// Redirige si l'utilisateur n'est pas admin
if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit(); // Permet de bloquer l'execution de la suite du code de la page.
}

//************************//
// SUPRESSION DU VEHICULE //
//************************//
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_voiture'])) {

    // //Récupération des données de la voitura voiture pour pouvoir supprimer sa photo.
    $recup_nom_photo = $pdo->prepare("SELECT * FROM voiture WHERE id_voiture = :id_voiture");
    $recup_nom_photo->bindParam(':id_voiture', $_GET['id_voiture'], PDO::PARAM_STR);
    $recup_nom_photo->execute();

    if ($recup_nom_photo->rowCount() > 0) {
        $recup = $recup_nom_photo->fetch(PDO::FETCH_ASSOC);
        unlink(ROOT_PATH . IMG_DIRECTORY . $recup['photo']);
    }


    $id_voiture= $_GET['id_voiture'];
    $supprimer = $pdo->prepare("DELETE FROM voiture WHERE id_voiture = :id_voiture");
    $supprimer->bindParam(':id_voiture', $id_voiture, PDO::PARAM_STR);
    $supprimer->execute();

    if ($supprimer->rowCount() > 0) {
        $msg .= '<div class="alert alert-success">La voiture numéro ' . $id_voiture . ' a bien été supprimé. </div>';
    }
}

$id_voiture = ''; // Champ caché du formulaire réservé à la modification
$marque = '';
$modele = '';
$tarif24 = '';
$tarif48 = '';
$tarifSemaine = '';
$caution = '';
$photo = '';

//******************************************//
// RECUPERATION DES DONNEES SI MODIFICATION //
//******************************************//

if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_voiture'])) {
    $modification = $pdo->prepare("SELECT * FROM voiture WHERE id_voiture = :id_voiture");
    $modification->bindParam(':id_voiture', $_GET['id_voiture'], PDO::PARAM_STR);
    $modification->execute();

    if ($modification->rowCount() > 0) {
        $infos = $modification->fetch(PDO::FETCH_ASSOC);
        $id_voiture = $infos['id_voiture'];
        $marque= $infos['marque'];
        $modele = $infos['modele'];
        $tarif24 = $infos['tarif24'];
        $tarif48 = $infos['tarif48'];
        $tarifSemaine = $infos['tarifSemaine'];
        $caution = $infos['caution'];
        $photo = $infos['photo'];
    }
}

//***********************//
// Enregistrement en BDD //
//***********************//

if (isset($_POST['marque']) && isset($_POST['modele']) && isset($_POST['tarif24']) && isset($_POST['tarif48']) && isset($_POST['tarifSemaine']) && isset($_POST['caution']) ) {

    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $tarif24 = trim($_POST['tarif24']);
    $tarif48 = trim($_POST['tarif48']);
    $tarifSemaine = trim($_POST['tarifSemaine']);
    $caution = trim($_POST['caution']);

    // Récupération de l'id si modification
    if (!empty($_POST['id_voiture'])) {
        $id_voiture = trim($_POST['id_voiture']);
    }

    $erreur = false;
    // Contôle sur la disponibilité de la reference car unique en BDD

    //Contrôle : la marque doit être définie
    if (empty($marque)) {
        $msg .= '<div class="alert alert-danger mb-3 ">⚠ La voiture n\'a pas de marque !</div>';
        $erreur = true;
    }

    //Contrôle : le modele doit être défini
    if (empty($modele)) {
        $msg .= '<div class="alert alert-danger mb-3 ">⚠ La voiture n\'a pas de modèle !</div>';
        $erreur = true;
    }

    // Contrôle : Le tarif 24h doit être numérique
    if (!is_numeric($tarif24)) {
        $msg .= '<div class="alert alert-warning mb-3 ">⚠ Le tarif 24h doit être défini.</div>';
        $error = true;
    } else {

        // Contrôle : si le tarif 48h n'est pas défini il est mit au double de celui de 24h
        if (!is_numeric($tarif48)) {
            $tarif48 = intval($tarif24) * 2;
            $msg .= '<div class="alert alert-warning mb-3 ">⚠ Le tarif 48h n\'étant pas renseigné, a été mis au double de celui de 24h.</div>';
        }

        // Contrôle : si le tarif Semaine n'est pas défini il est mit au sept de celui de 24h
        if (!is_numeric($tarifSemaine)) {
            $tarifSemaine = intval($tarif24) * 7;
            $msg .= '<div class="alert alert-warning mb-3 ">⚠ Le tarif ebdomadaire n\'étant pas renseigné, a été mis au sept de celui de 24h.</div>';
        }
    }

    // Contrôle : La caution doit être numérique
    if (!is_numeric($tarif24)) {
        $msg .= '<div class="alert alert-warning mb-3 ">⚠ La caution doit être défini.</div>';
        $error = true;
    }

    // Contrôle sur la photo
    //Les fichiers d'un formulaire vont dans la superglobale $_FILES(obligatoire de mettre l'attribut enctype sur le form sinon les fichiers ne seront jamais récupérés !)
    if (!empty($_FILES['photo']['name'])) {
        // Attention car $_FILES n'est jamais vide à la validation du form.
        //Il faut aller jusqu'au ['name'] du fichier chargé afin d'être sûr qu'un fichier soit présent.

        //Afin d'être sûr que le fichier chargé est bien une image avec un extension valide pour le web, nous allons recupérer l'extension du fichier pour le comparer avec des extension valides
        $tab_extension_valide = ['png', 'gif', 'jpg', 'jpeg', 'webp'];

        // Pour vérifier l'extension, nous allons découper le nom du fichier en partant de la fin, on remonte au permier point trouvé,  et on récupère tout depuis ce point : strrchr()

        $extension = strtolower(substr(strrchr($_FILES['photo']['name'], '.'), 1));
        // exemple : pour le fichier truc.png on récupère .png
        //Ensuite on enleve le point dans la chaine de caractère recupéré pour avoir png avec substr
        // Puis on met tout en minuscule pour pas avoir de souci,  avec strtolower()

        //On va maintenant comparer les extensions reçu avec les extension acceptées qui sont dans le tableau tab_extension_valide
        // in_array() renvoie true si le premier argument est présent dans les valeurs du tableau fourni en deuxième argument
        if (in_array($extension, $tab_extension_valide) && $erreur == false) {
            // On vérifie si $erreur == false car si il est true, le produit ne sera pas enregistré donc pas besoin d'enregistrer l'image.
            // Extension ok on va lancer la copie
            // On rajoute la référence (unique) devant le nom de l'image afin de ne pas ecraser une image deja enregistré du meme nom
            $photo = $marque . '_' . $modele . '_' .  $_FILES['photo']['name'];

            // Chemin du dossier pour enregistrer l'image :
            $chemin_enregistrement_image = ROOT_PATH . IMG_DIRECTORY . $photo;
            // On copie : copy(chemin_origine, chemin_cible);
            copy($_FILES['photo']['tmp_name'], $chemin_enregistrement_image);
        } else {
            //cas d'erreur
            $msg .= '<div class="alert alert-danger mb-3 ">⚠ L\'extension de l\'image n\'est pas valide.<br> Vérifiez si votre image est bien en png, gif, jpg, jpeg ou webp .</div>';
            $erreur = true;
        } // Si extension ok
    } // Si une image est chargé

    //*************************************//
    // Enregistrement de la voiture en bdd //
    //*************************************//

    if ($erreur == false) {

        if (empty($id_voiture)) {

            $enregistrement = $pdo->prepare("INSERT INTO voiture (marque, modele, tarif24, tarif48, tarifSemaine, caution, photo) VALUES (:marque, :modele, :tarif24, :tarif48, :tarifSemaine, :caution, :photo)");
            $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
        } else {
            $enregistrement = $pdo->prepare("UPDATE voiture SET marque = :marque, modele = :modele, tarif24 = :tarif24, tarif48 = :tarif48, tarifSemaine = :tarifSemaine, caution = :caution WHERE id_voiture = :id_voiture");
            $enregistrement->bindParam(':id_voiture', $id_voiture, PDO::PARAM_STR);
        }
        $enregistrement->bindParam(':marque', $marque, PDO::PARAM_STR);
        $enregistrement->bindParam(':modele', $modele, PDO::PARAM_STR);
        $enregistrement->bindParam(':tarif24', $tarif24, PDO::PARAM_STR);
        $enregistrement->bindParam(':tarif48', $tarif48, PDO::PARAM_STR);
        $enregistrement->bindParam(':tarifSemaine', $tarifSemaine, PDO::PARAM_STR);
        $enregistrement->bindParam(':caution', $caution, PDO::PARAM_STR);
        $enregistrement->execute();

        //On redirige sur la même page afin de ne plus avoir la mémoire du formulaire si on recharge la page
        header('location: gestion_voiture.php?oui='.$chemin_enregistrement_image);
    }
}

//***************************//
// Récupération des voitures //
//***************************//
$voitures = $pdo->query("SELECT * FROM voiture ORDER BY marque, modele");

include "../inc/header.inc.php";
?>


<h1> Gestion voiture</h1>

<main class="container-fluid">

    <div class="container">
        <div class="row mt-5">

            <div class="col-12"><?= $msg ?></div>

            <div class="col-12">
                <form action="" method="POST" class="border rounded p-3 row" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= $id_voiture ?>">

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="marque"> Marque : </label>
                        <input type="text" name="marque" id="marque" class="form-control" value="<?= $marque?>">
                    </div>
                    <div class="mb-3">
                        <label for="modele"> Modèle : </label>
                        <input type="text" name="modele" id="modele" class="form-control" value="<?= $modele?>">
                    </div>
                    <div class="mb-3">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="tarif24"> Tarif 24h : </label>
                        <input type="text" name="tarif24" id="tarif24" class="form-control" value="<?= $tarif24?>">
                    </div>
                    <div class="mb-3">
                        <label for="tarif48"> Tarif 48h : </label>
                        <input type="text" name="tarif48" id="tarif48" class="form-control" value="<?= $tarif48?>">
                    </div>
                    <div class="mb-3">
                        <label for="tarifSemaine"> Tarif Hebdomadaire : </label>
                        <input type="text" name="tarifSemaine" id="tarifSemaine" class="form-control" value="<?= $tarifSemaine?>">
                    </div>
                    <div class="mb-3">
                        <label for="caution"> Caution : </label>
                        <input type="text" name="caution" id="caution" class="form-control" value="<?= $caution?>">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" id="enregistrement" class="btn btn-outline-primary w-100">Enregistrement <i class="fas fa-sign-in-alt"></i></button>
                </div>
                </form>
            </div>
        </div>





<div class="container">
<div class="col-12 mt-5">
    <p class="alert alert-secondary">Il y a <?= $voitures->rowCount() ?> voitures enregistrées.</p>
    <hr>
    <table class="table table-bordered text-center w-100">
        <thead class="">
            <tr>
            <th>ID voiture</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Tarif 24h</th>
            <th>Tarif 48h</th>
            <th>Tarif Hebdo</th>
            <th>Caution</th>
            <th>Photo</th>
            <th>Modif</th>
            <th>Suppr</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($voiture = $voitures->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $voiture['id_voiture'] . '</td>';
            echo '<td>' . $voiture['marque'] . '</td>';
            echo '<td>' . $voiture['modele'] . '</td>';
            echo '<td>' . $voiture['tarif24'] . '</td>';
            echo '<td>' . $voiture['tarif48'] . '</td>';
            echo '<td>' . $voiture['tarifSemaine'] . '</td>';
            echo '<td>' . $voiture['caution'] . '</td>';
            echo '<td><img src="'. URL .'img/' . $voiture['photo'] . '" width="100"></td>';
            echo '<td><a href="?action=modifier&id=' . $voiture['id_voiture'] . '" class="btn btn-warning">editer</a></td>';
            // AJOUTER UNE VALIDATION SUR LA SUPRESSION
            echo '<td><a href="?action=supprimer&id=' . $voiture['id_voiture'] . '" class="btn btn-danger">suprimer</a></td>';
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
