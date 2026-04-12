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

// 3. Connexions et dépendances
require_once '../include/db.php'; 

// 4. Récupération des matières pour la liste déroulante
$sql = "SELECT m.id_matiere, m.nom_matiere, f.nom_filiere, fo.nom_formation 
        FROM matiere m
        JOIN filiere f ON m.id_filiere = f.id_filiere
        JOIN formation fo ON f.id_formation = fo.id_formation
        ORDER BY fo.nom_formation ASC, f.nom_filiere ASC, m.nom_matiere ASC";

$liste_matieres = $pdo->query($sql)->fetchAll();

// 5. On affiche le header
include '../include/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 15px 15px 0 0;">
                    <h4 class="mb-0 text-white"><i class="fa fa-file-upload me-2"></i>Ajouter une Épreuve</h4>
                </div>
                <div class="card-body p-4">
                    
                    <!-- LE FORMULAIRE COMMENCE ICI ET ENVELOPPE TOUT -->
                    <form action="epreuves_insert.php" method="POST" enctype="multipart/form-data">
                        
                        <!-- Choix de la Matière -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-primary">Destination (Niveau > Filière > Matière)</label>
                            <select name="id_matiere" class="form-select form-control-lg border-primary" required>
                                <option value="" disabled selected>-- Où ranger cette épreuve ? --</option>
                                <?php foreach($liste_matieres as $row): ?>
                                    <option value="<?= $row['id_matiere'] ?>">
                                        [<?= htmlspecialchars($row['nom_formation']) ?>] -> <?= htmlspecialchars($row['nom_filiere']) ?> -> <?= htmlspecialchars($row['nom_matiere']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <!-- Année -->
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Année</label>
                                <input type="number" name="annee" class="form-control" value="<?= date('Y') ?>" required>
                            </div>
                            
                            <!-- Type de Session -->
                            <div class="col-md-8 mb-4">
                                <label class="form-label fw-bold">Type de Session</label>
                                <select name="session_type" class="form-select" required>
                                    <option value="Session Normale">Session Normale (Examen)</option>
                                    <option value="Session de Rattrapage">Session de Rattrapage</option>
                                    <option value="Session Spéciale">Session Spéciale</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fichier PDF -->
                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold">Fichier PDF</label>
                                <input type="file" name="pdf" class="form-control" accept=".pdf" required>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="submit" name="upload" class="btn btn-primary w-100 py-3 shadow">
                                <i class="fa fa-cloud-upload-alt me-2"></i>Publier l'épreuve
                            </button>
                            <a href="epreuves_list.php" class="btn btn-outline-secondary w-100 py-3">Annuler</a>
                        </div>

                    </form> <!-- LE FORMULAIRE SE FERME BIEN ICI -->
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>