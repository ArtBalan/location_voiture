<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
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
          <a class="nav-link active" aria-current="page" href="#">Menu</a>
        </li>

      <li class="nav-item">
          <a class="nav-link" href="voiture.php">Voitures</a>
        </li>
       
          <li class="nav-item">
          <a class="nav-link" href="inscription.php">Inscription</a>
        </li>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="connexion.php">Connexion</a>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>


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
            include_once "footer.inc.php";
        ?>
