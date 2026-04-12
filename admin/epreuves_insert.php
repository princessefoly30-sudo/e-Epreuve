<?php 
// 1. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. Sécurité : Vérification de l'accès admin
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 3. Connexion à la base de données
require_once '../include/db.php'; 

// 4. TRAITEMENT DU FORMULAIRE
if (isset($_POST['upload']) && isset($_FILES['pdf'])) {
    
    // Récupération et sécurisation des données du formulaire
    $id_matiere   = intval($_POST['id_matiere']);
    $annee         = intval($_POST['annee']);
    $session_type = htmlspecialchars($_POST['session_type']);

    // Informations sur le fichier PDF
    $nom_original = $_FILES['pdf']['name'];
    $tmp_nom      = $_FILES['pdf']['tmp_name'];
    $error_code   = $_FILES['pdf']['error'];

    // Vérification s'il n'y a pas d'erreur d'upload
    if ($error_code === 0) {
        
        // CRÉATION D'UN NOM UNIQUE (Pour éviter que Maintenance n'écrase Excel)
        $extension = pathinfo($nom_original, PATHINFO_EXTENSION);
        // On génère un nom du type : 1712345678_a1b2c3d4.pdf
        $nom_unique = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
        
        // Chemin de destination (Assure-toi que le dossier ../uploads existe)
        $destination = "../uploads/" . $nom_unique;

        // Déplacement du fichier du dossier temporaire vers le dossier uploads
        if (move_uploaded_file($tmp_nom, $destination)) {
            try {
                // 5. INSERTION DANS LA BASE DE DONNÉES
                $sql = "INSERT INTO epreuve (annee, session_type, fichier_pdf, id_matiere) 
                        VALUES (?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$annee, $session_type, $nom_unique, $id_matiere]);

                // Redirection vers la liste avec un message de succès
                header("Location: epreuves_list.php?msg=success");
                exit();

            } catch (PDOException $e) {
                // En cas d'erreur SQL, on supprime le fichier qui a été uploadé pour rien
                if (file_exists($destination)) {
                    unlink($destination);
                }
                die("Erreur SQL lors de l'insertion : " . $e->getMessage());
            }
        } else {
            // Erreur de déplacement du fichier
            header("Location: epreuves_add.php?error=upload_failed");
            exit();
        }
    } else {
        // Erreur liée au fichier (trop gros, corrompu, etc.)
        header("Location: epreuves_add.php?error=file_error");
        exit();
    }
} else {
    // Si on tente d'accéder au fichier directement sans passer par le formulaire
    header("Location: epreuves_add.php");
    exit();
}
?>