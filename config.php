<?php
// config.php

// Configuration de la connexion à la base de données
$host = "localhost"; // Adresse du serveur de base de données (généralement "localhost")
$dbname = "api_spa"; // Nom de la base de données
$username = "nick"; // Nom d'utilisateur de la base de données
$password = "passer"; // Mot de passe de la base de données

// Options de configuration pour PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Création de l'objet PDO pour la connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
