<?php 
// 1. Démarrer la session
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. Vérifier la sécurité (AVANT LE HEADER)
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit();
}

// 3. Charger la base de données et faire tes calculs/requêtes
require_once '../include/db.php'; 

// 4. SEULEMENT ICI, on affiche le visuel
include '../include/header.php'; 


// 3. Charger la base de données et faire tes calculs/requêtes
require_once '../include/db.php'; 

// 4. SEULEMENT ICI, on affiche le visuel
include '../include/header.php'; 

if (isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM matiere WHERE id_matiere = ?")->execute([$_GET['id']]);
}
header("Location: matieres_list.php");
exit();
?>