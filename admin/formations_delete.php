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

// On vérifie qu'on a bien un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // On prépare et on exécute la suppression
    $del = $pdo->prepare("DELETE FROM formation WHERE id_formation = ?");
    $del->execute([$id]);
}

// On repart direct vers la liste
header("Location: formations_list.php");
exit();
?>