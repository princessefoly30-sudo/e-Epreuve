<?php 
// 1. Démarrer la session
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. Vérifier la sécurité (AVANT TOUT AFFICHAGE)
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 3. Charger la base de données
require_once '../include/db.php'; 

// 4. TRAITEMENT DU FORMULAIRE (AVANT LE HEADER)
if (isset($_POST['valider'])) {
    // On récupère et on nettoie la donnée
    $nom = htmlspecialchars(trim($_POST['nom_formation']));

    if (!empty($nom)) {
        // Préparation de la requête SQL
        $sql = "INSERT INTO formation (nom_formation) VALUES (:nom)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute(['nom' => $nom])) {
            // Redirection après succès pour éviter de renvoyer le formulaire en rafraîchissant
            header("Location: formations_list.php?msg=success");
            exit();
        }
    }
}

// 5. SEULEMENT ICI, on affiche le visuel
include '../include/header.php'; 
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Administration</h6>
            <h1 class="mb-5">Ajouter une Formation</h1>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow p-4 border-0" style="border-radius: 15px;">
                    <form method="POST">
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold text-dark">Nom de la formation (ex: Licence 2)</label>
                            <input type="text" name="nom_formation" class="form-control" placeholder="Entrez le nom..." required>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="formations_list.php" class="btn btn-secondary w-50 py-3">Annuler</a>
                            <button type="submit" name="valider" class="btn btn-primary w-50 py-3 shadow">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>