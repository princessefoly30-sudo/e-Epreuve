<?php 
// 1. Démarrage de la session et sécurité
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once '../include/db.php'; 

// Utilise la variable de session que tu as définie (logged ou admin_logged)
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 2. Vérification de l'ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // 3. RÉCUPÉRATION DU NOM RÉEL DU FICHIER PDF
    // On sélectionne 'fichier_pdf' et non 'annee'
    $req = $pdo->prepare("SELECT fichier_pdf FROM epreuve WHERE id_epreuve = ?");
    $req->execute([$id]);
    $epreuve = $req->fetch();

    if ($epreuve) {
        $nom_du_fichier = $epreuve['fichier_pdf'];
        
        // Vérifie si tu stockes dans /uploads/ ou /img/ 
        // (Ton insert utilisait ../uploads/)
        $chemin_complet = "../uploads/" . $nom_du_fichier;

        // 4. SUPPRESSION DU FICHIER PHYSIQUE
        if (!empty($nom_du_fichier) && file_exists($chemin_complet)) {
            unlink($chemin_complet); 
        }

        // 5. SUPPRESSION DANS LA BASE DE DONNÉES
        $delete = $pdo->prepare("DELETE FROM epreuve WHERE id_epreuve = ?");
        $delete->execute([$id]);
    }
}

// 6. Redirection avec un message de confirmation
header("Location: epreuves_list.php?msg=deleted");
exit();
?>