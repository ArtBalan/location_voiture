<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title>Eshop</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container-fluid">
     <a class="navbar-brand" href="#">Golden location</a>
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Menu</a>
        </li>
     <li>
        <a class="nav-link" href="<?= URL ?>voiture.php">Voiture</a>
     </li>

     <?php


     //=============================================//
     // Menu admin                                  //
     //=============================================//
     if(user_is_admin()){
         // gestion voiture
         echo '<li class="nav-item">';
         echo '<a class="nav-link" href="' . URL . 'admin/gestion_voiture.php">Gestion Voiture</a>';
         echo '</li>';
     }

     //=============================================//
     // menu de connexion, deconnexion, inscription //
     //=============================================//
     if(user_is_connected()){
         // page profil
         echo '<li class="nav-item">';
         echo '<a class="nav-link" href="'. URL . 'profil.php">Profil</a>';
         echo '</li>';
         // page deconnexion
         echo '<li class="nav-item">';
         echo '<a class="nav-link" href="'. URL . 'connexion.php?action=deconnexion">Deconnexion</a>';
         echo '</li>';
     } else {
         // page inscription
         echo '<li class="nav-item">';
         echo '<a class="nav-link" href="' . URL . 'inscription.php">Inscription</a>';
         echo '</li>';
         // page connexion
         echo '<li class="nav-item">';
         echo '<a class="nav-link " href="' . URL . 'connexion.php">Connexion</a>';
         echo '</li>';
     }
     ?>

      </ul>
    </div>
  </div>
</nav>
