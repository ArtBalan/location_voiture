<?php

// PDO initialisation
$host = 'mysql:host=localhost;dbname=location_voiture';
$login = 'root';
$password = '';

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

$pdo = new PDO($host, $login, $password, $options);

// Variable to display info message
$msg = "";

// Juste a regulare session opening
session_start();


// Racine serveur (le chemin du disque dur jusqu'au dossier racine de notre serveur). On récupère l'info dans la superglobale $_SERVER afin que ça s'adapte toujours
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);


// A CHANGER AVEC VOS CHEMIN
// BILEL
// define('URL', 'http://localhost/location_voiture-master/');
//ARTHUR
define('URL', 'http://localhost/devweb/location_voiture-master/');
//KEVIN
// define('URL', 'http://localhost/PHP/location_voiture-master/');

//Constante pour l'enregistrement des images sur la page gestion_voiture.php
// A CHANGER AVEC VOS CHEMIN
//BILEL
// define('IMG_DIRECTORY', '/location_voiture-master/img/');
// ARTHUR
define('IMG_DIRECTORY', '/devweb/location_voiture-master/img/');
//KEVIN
// define('IMG_DIRECTORY', '/PHP/location_voiture-master/img/');
