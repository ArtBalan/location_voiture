<?php
include_once "inc/header.inc.php";
?>

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
