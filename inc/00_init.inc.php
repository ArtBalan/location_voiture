<?php
// PDO initialisation
$host = "mysql:host=localhost;dbname=location_voiture";
$login = "root";
$password = "";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
];

$pdo = new PDO($host, $login, $password, $options);

// Variable to display info message
$msg = "";

// Juste a regulare session opening
session_start();

// A CHANGER AVEC VOS CHEMIN
define("URL", "http://localhost/devweb/location_voiture-master/");

// Racine serveur (le chemin du disque dur jusqu'au dossier racine de notre serveur). On récupère l'info dans la superglobale $_SERVER afin que ça s'adapte toujours
define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"]);

//Constante pour l'enregistrement des images sur la page gestion_voiture.php
// A CHANGER AVEC VOS CHEMIN
define("IMG_DIRECTORY", "/devweb/location_voiture-master/assets/img/voiture");

$currentPage = basename($_SERVER["PHP_SELF"]);
