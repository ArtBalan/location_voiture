<?php
include "inc/init.inc.php";
include "inc/function.inc.php";

if(!user_is_connected()){
    header('location: connexion.php');
    exit();
}

if($_SESSION["membre"]["sexe"]== "m"){
    $affichage_sexe ="homme";
}else{
    $affichage_sexe="femme";
}

if($_SESSION["membre"]["statut"]=="0"){
    $affichage_statut ="membre";
}elseif($_SESSION["membre"]["statut"]=="2"){
    $affichage_statut= "administrateur";
}

//debut de l'affichage ci-dessus
include "inc/header.inc.php";
//echo $_SESSION["membre"]["adresse"] permet d'affiche des finformations en haut de la page
?>

<main class="container-fluid text-indigo">
    <div class="bg-light p5 text border-indigo-buttom">
    <h1><i class="fas fa-user"></i><i class="fas fa-user"></i> </i></h1>
</div>
<div class="container ">
        <div class="row mt-5 ">
            <div class="col-12 ">
                <h2 class="mb-3">Bonjour <?php echo $_SESSION['membre']['prenom']; ?></h2>
                <h5 class="mb-3">Vos informations personnelles : </h5>
            </div>
            <div class="col-sm-6 ">

                <ul class="list-group ">

                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Pseudo : </b><?php echo $_SESSION['membre']['pseudo']; ?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Prénom : </b><?php echo $_SESSION['membre']['prenom']; ?></li>
                    <li class="list-group-item bg-indigo rounded mb-3"> <b> Nom : </b> <?php echo $_SESSION ["membre"] ["nom"];?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Email : </b><?php echo $_SESSION['membre']['email']; ?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Sexe : </b><?php echo $affichage_sexe; ?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Ville : </b><?php echo $_SESSION['membre']['ville']; ?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Code postal : </b><?php echo $_SESSION['membre']['cp']; ?></li>
                    <li class="list-group-item bg-indigo  rounded mb-3"><b>Adresse : </b><?php echo $_SESSION['membre']['adresse']; ?></li>
                    <li class="list-group-item bg-indigo rounded mb-3"><b>Statut : </b><?php echo $affichage_statut; ?></li>
                </ul>
            </div>
            <div class="col-sm-6 align-items-center">
                <img src="./img/chien.jpg" class="d-flex rounded" alt="photo de profil">
             </div>




        </div>
</main>

<?php
include 'inc/footer.inc.php';
