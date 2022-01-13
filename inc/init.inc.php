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
