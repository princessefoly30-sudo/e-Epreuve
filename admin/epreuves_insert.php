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
    $taille_fichier = $_FILES['pdf']['size'];

    // TABLEAU DES ERREURS D'UPLOAD (Pour debug si ça fonctionne pas)
    $erreurs_upload = [
        0 => "Pas d'erreur",
        1 => "Fichier trop gros (dépasse upload_max_filesize)",
        2 => "Fichier trop gros (dépasse post_max_size)",
        3 => "Fichier partiellement uploadé",
        4 => "Aucun fichier sélectionné",
        6 => "Pas de dossier temporaire",
        7 => "Impossible d'écrire sur le disque",
        8 => "Extension bloquée par PHP"
    ];

    // Vérification s'il n'y a pas d'erreur d'upload
    if ($error_code === 0) {
        
        // SÉCURITÉ : Vérifier que c'est bien un PDF
        $extension = strtolower(pathinfo($nom_original, PATHINFO_EXTENSION));
        
        // Petite sécurité pour éviter d'uploader n'importe quoi
        if ($extension !== 'pdf') {
            header("Location: epreuves_add.php?error=not_pdf");
            exit();
        }
        
        // Vérifier la taille du fichier (max 50 MB)
        $max_size = 50 * 1024 * 1024; // 50 MB en bytes
        if ($taille_fichier > $max_size) {
            header("Location: epreuves_add.php?error=too_large");
            exit();
        }

        // CRÉATION D'UN NOM UNIQUE (Pour éviter que Maintenance n'écrase Excel)
        // On génère un nom du type : 1712345678_a1b2c3d4.pdf
        $nom_unique = time() . "_" . bin2hex(random_bytes(4)) . ".pdf";
        
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
            // Erreur de déplacement du fichier (probablement un souci de permissions)
            header("Location: epreuves_add.php?error=move_failed");
            exit();
        }
    } else {
        // Erreur liée au fichier (trop gros, corrompu, etc.)
        // On envoie le code d'erreur pour pouvoir afficher le message approprié
        $error_msg = $erreurs_upload[$error_code] ?? "Erreur inconnue";
        header("Location: epreuves_add.php?error=file_error&code=" . $error_code . "&msg=" . urlencode($error_msg));
        exit();
    }
} else {
    // Si on tente d'accéder au fichier directement sans passer par le formulaire
    header("Location: epreuves_add.php");
    exit();
}
?>