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

// 3. Connexions
require_once '../include/db.php'; 

// 4. Vérification de l'ID passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: epreuves_list.php');
    exit();
}

$id = intval($_GET['id']);

// 5. TRAITEMENT DE LA MISE À JOUR
if (isset($_POST['update'])) {
    $annee = $_POST['annee'];
    $session_type = $_POST['session_type']; // Correction : On récupère la session choisie
    $id_mat = $_POST['id_matiere'];
    
    // On récupère d'abord les infos actuelles pour garder l'ancien PDF si pas de nouveau
    $check = $pdo->prepare("SELECT fichier_pdf FROM epreuve WHERE id_epreuve = ?");
    $check->execute([$id]);
    $current_epreuve = $check->fetch();
    $nom_fichier = $current_epreuve['fichier_pdf'];

    // Si l'utilisateur a choisi un nouveau fichier PDF
    if (!empty($_FILES['pdf']['name'])) {
        $tmp_nom = $_FILES['pdf']['tmp_name'];
        $nouveau_nom = time() . "_" . str_replace(' ', '_', $_FILES['pdf']['name']);
        $destination = "../uploads/" . $nouveau_nom;

        if (move_uploaded_file($tmp_nom, $destination)) {
            // Supprimer l'ancien fichier physique pour ne pas encombrer le serveur
            if (file_exists("../uploads/" . $nom_fichier) && !empty($nom_fichier)) {
                unlink("../uploads/" . $nom_fichier);
            }
            $nom_fichier = $nouveau_nom;
        }
    }

    // MISE À JOUR : On ajoute bien session_type dans la requête SQL
    $update = $pdo->prepare("UPDATE epreuve SET annee = ?, session_type = ?, id_matiere = ?, fichier_pdf = ? WHERE id_epreuve = ?");
    $update->execute([$annee, $session_type, $id_mat, $nom_fichier, $id]);

    header("Location: epreuves_list.php?msg=updated");
    exit();
}

// 6. RÉCUPÉRATION DES DONNÉES POUR REMPLIR LE FORMULAIRE
$stmt = $pdo->prepare("SELECT * FROM epreuve WHERE id_epreuve = ?");
$stmt->execute([$id]);
$epreuve = $stmt->fetch();

if (!$epreuve) { header('Location: epreuves_list.php'); exit(); }

// Liste des matières pour le Select
$sql_mat = "SELECT m.id_matiere, m.nom_matiere, f.nom_filiere, fo.nom_formation 
            FROM matiere m
            JOIN filiere f ON m.id_filiere = f.id_filiere
            JOIN formation fo ON f.id_formation = fo.id_formation
            ORDER BY fo.nom_formation ASC, f.nom_filiere ASC, m.nom_matiere ASC";
$liste_matieres = $pdo->query($sql_mat)->fetchAll();

// 7. Affichage du Header
include '../include/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0 text-white"><i class="fa fa-edit me-2"></i>Modifier l'Épreuve</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" enctype="multipart/form-data">
                        
                        <!-- Choix de la Matière -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">Destination (Niveau > Filière > Matière)</label>
                            <select name="id_matiere" class="form-select form-control-lg border-primary" required>
                                <?php foreach($liste_matieres as $row): ?>
                                    <option value="<?= $row['id_matiere'] ?>" <?= ($row['id_matiere'] == $epreuve['id_matiere']) ? 'selected' : '' ?>>
                                        [<?= htmlspecialchars($row['nom_formation']) ?>] -> <?= htmlspecialchars($row['nom_filiere']) ?> -> <?= htmlspecialchars($row['nom_matiere']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <!-- Année -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Année de l'épreuve</label>
                                <input type="number" name="annee" class="form-control" value="<?= htmlspecialchars($epreuve['annee']) ?>" required>
                            </div>
                            <!-- Type de Session (NOUVEAU) -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Type de Session</label>
                                <select name="session_type" class="form-select" required>
                                    <option value="Session Normale" <?= ($epreuve['session_type'] == 'Session Normale') ? 'selected' : '' ?>>Session Normale</option>
                                    <option value="Session de Rattrapage" <?= ($epreuve['session_type'] == 'Session de Rattrapage') ? 'selected' : '' ?>>Session de Rattrapage</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fichier PDF -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Remplacer le PDF <small class="text-muted">(Laissez vide pour conserver l'actuel)</small></label>
                            <input type="file" name="pdf" class="form-control" accept=".pdf">
                            <div class="form-text mt-2 text-truncate">
                                <i class="fa fa-file-pdf text-danger"></i> Fichier actuel : <strong><?= htmlspecialchars($epreuve['fichier_pdf']) ?></strong>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="update" class="btn btn-primary w-100 py-3 shadow">
                                <i class="fa fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="epreuves_list.php" class="btn btn-outline-secondary w-100 py-3">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>