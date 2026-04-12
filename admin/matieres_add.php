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



// 1. On prépare TOUTES les données AVANT d'afficher le formulaire
// On joint 'filiere' et 'formation' pour avoir le nom du niveau (L1, L2...)
$sql = "SELECT f.*, fo.nom_formation 
        FROM filiere f 
        JOIN formation fo ON f.id_formation = fo.id_formation 
        ORDER BY fo.nom_formation ASC, f.nom_filiere ASC";
$filieres = $pdo->query($sql)->fetchAll();

// 2. Traitement du formulaire quand on clique sur valider
if (isset($_POST['valider'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $id_filiere = $_POST['id_filiere'];

    if (!empty($nom) && !empty($id_filiere)) {
        $ins = $pdo->prepare("INSERT INTO matiere (nom_matiere, id_filiere) VALUES (?, ?)");
        $ins->execute([$nom, $id_filiere]);
        
        // Redirection après succès
        header("Location: matieres_list.php");
        exit();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-white"><i class="fa fa-book me-2"></i>Nouvelle Matière</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nom de la Matière</label>
                            <input type="text" name="nom" class="form-control form-control-lg border-primary" placeholder="ex: Algorithme" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">Destination (Niveau > Filière)</label>
                            <select name="id_filiere" class="form-select form-control-lg border-primary" required>
                                <option value="" disabled selected>-- Choisir l'emplacement --</option>
                                <?php foreach($filieres as $f): ?>
                                    <option value="<?= $f['id_filiere'] ?>">
                                        [<?= htmlspecialchars($f['nom_formation']) ?>] -> <?= htmlspecialchars($f['nom_filiere']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">C'est ici que tu décides dans quel niveau ranger cette matière.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="valider" class="btn btn-primary w-100 py-3 shadow">
                                <i class="fa fa-save me-1"></i> Enregistrer
                            </button>
                            <a href="matieres_list.php" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fa fa-arrow-left me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>