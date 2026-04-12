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

// 4. TRAITEMENT DU FORMULAIRE (DOIT ÊTRE AVANT TOUT HTML)
if (isset($_POST['save'])) {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $id_f = $_POST['id_form'];

    if (!empty($nom) && !empty($id_f)) {
        $stmt = $pdo->prepare("INSERT INTO filiere (nom_filiere, id_formation) VALUES (?, ?)");
        $stmt->execute([$nom, $id_f]);

        // Redirection immédiate après l'enregistrement
        header("Location: filieres_list.php?msg=success");
        exit(); // Toujours mettre exit() après un header Location
    }
}

// 5. RÉCUPÉRATION DES DONNÉES POUR LE SELECT
$formations = $pdo->query("SELECT * FROM formation ORDER BY nom_formation ASC")->fetchAll();

// 6. MAINTENANT ON PEUT AFFICHER LE DESIGN
include '../include/header.php'; 
?>

<div class="container py-5">
    <!-- Bouton Retour -->
    <div class="mb-4">
        <a href="filieres_list.php" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow border-0 mx-auto" style="max-width: 600px; border-radius: 15px;">
        <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
            <h5 class="mb-0 text-white">Ajouter une Filière</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom de la Filière</label>
                    <input type="text" name="nom" class="form-control" placeholder="ex: Informatique de Gestion" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Rattacher au Niveau d'Étude</label>
                    <select name="id_form" class="form-select" required>
                        <option value="" selected disabled>Choisir un niveau...</option>
                        <?php foreach($formations as $fo): ?>
                            <option value="<?= $fo['id_formation'] ?>">
                                <?= htmlspecialchars($fo['nom_formation']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="save" class="btn btn-primary w-100 py-2 shadow-sm">
                    <i class="fa fa-save me-2"></i>Enregistrer la Filière
                </button>
            </form>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>