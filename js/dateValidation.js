
//*******************//
// Objet creneau //
//*******************//
class Creneau {
  constructor(debut, fin){
    this.debut = debut;
    this.fin = fin;
  }
}

//**********************//
// Déclaration des vars //
//**********************//
// on récupère les éléments ayant la classe dateItem
let listItem = document.getElementsByClassName('dateItem');
//on récupère les deux inputs de type date
let debutInput = document.getElementById("date_debut");
let finInput = document.getElementById("date_fin");
let btnReservation = document.getElementById('btn_reservation');
// On créer un tableau qui va contenir les crénaux de réservation
let creneauList = [];

//**************************//
// Récupération des données //
//**************************//

// Pour chaque élémént ayant la classe dateItem
for(var i=0; i < listItem.length; i++ ){
  // On récupère les dates stocker dans les éléments html (generer par le php)
  let dateDebut = listItem[i].dataset.debut;
  let dateFin = listItem[i].dataset.fin;
  // On créer un objet Crenau, ayant les date et on l'ajoute a la liste
  creneauList.push(new Creneau(dateDebut,dateFin));
}

// Dés que un des inputs changent on appel la fonction update
debutInput.addEventListener('change', e => update());
finInput.addEventListener('change', e => update());

// Lorsque un des inputs changent
function update(){
  // recupération du créneau entré par l'utilisateur
  dateDebut = parseInt(debutInput.value.replaceAll('-',''));
  dateFin = parseInt(finInput.value.replaceAll('-',''));
  let currentCreneau = new Creneau(dateDebut,dateFin);

  // verification de la validité du créneau
  let overlap = false;
  let i = 0;
  let length = creneauList.length;
  while(!overlap && i<length){
      overlap = date_overlap(currentCreneau, creneauList[i]);
      i++;
    }

  // Si déjà reservé
  if(overlap){
    btnReservation.disabled = true;
    btnReservation.value = "Le vehicule est déjà réserver sur ces dates"
  } else {
    btnReservation.disabled = false;
    btnReservation.value = "Reserver"
  }

  // VERIFICATION DE SI LA DATE DE DEBUT EST SUPERIEUR A LA DATE DE FIN
  if(currentCreneau.debut > currentCreneau.fin){
    btnReservation.disabled = true;
    btnReservation.value = "Les deux dates ne sont pas dans le bonne ordre"
  }

}

function date_overlap(dateA,dateB){
    return (dateA.debut <= dateB.fin) && (dateA.fin >= dateB.debut);
}
