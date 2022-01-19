<?php
include "inc/init.inc.php";
include "inc/function.inc.php";

//***************************//
// Récupération des voitures //
//***************************//
$voitures = $pdo->query("SELECT * FROM voiture ORDER BY marque, modele");

include_once "inc/header.inc.php";
?>

<div class="card-group">
<?php
  while ($voiture = $voitures->fetch(PDO::FETCH_ASSOC)) {
?>
  <div class="card">
    <img src="img/<?= $voiture['image'] ?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><?= $voiture['marque'] ?> GLA AMG </h5>
      <p class="card-text"> <?= $voiture['modele'] ?>
      <br>
        Tarif <?= $voiture['tarif24'] ?>€ 24h.
        <br>
        <?= $voiture['tarif48'] ?>€ 48h.
        <br>
        <?= $voiture['tarifSemaine'] ?>€ la semaine.
        <br>
        Caution <?= $voiture['caution'] ?>€.
      </p>
      <a href="reservation.php?id=<?= $voiture['id'] ?>" class="btn btn-primary">Reserver</a>
    </div>
  </div>

  <?php
    }
  ?>
<?php
  include_once "inc/footer.inc.php";
?>
