<?php 
include '../include/db.php'; 

// On vérifie qu'on a bien un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // On prépare et on exécute la suppression
    $del = $pdo->prepare("DELETE FROM communes WHERE id_commune = ?");
    $del->execute([$id]);
}

// On repart direct vers la liste
header("Location: list.php");
exit();
?>