<?php
$host = 'localhost';
$db   = 'gestion_epreuve'; // Remplace par le vrai nom
$user = 'root';
$pass = ''; // Vide par défaut sur Wamp

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>