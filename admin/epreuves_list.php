<?php 
// 1. Démarrer la session
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. Vérifier la sécurité (AVANT TOUT AFFICHAGE)
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 3. Charger la base de données
require_once '../include/db.php'; 

// 4. LOGIQUE : Récupération des données (On fait le SQL AVANT le header)
$sql = "SELECT e.*, m.nom_matiere 
        FROM epreuve e 
        JOIN matiere m ON e.id_matiere = m.id_matiere 
        ORDER BY e.id_epreuve DESC";
$epreuves = $pdo->query($sql)->fetchAll();

// 5. SEULEMENT ICI, on affiche le visuel
include '../include/header.php'; 
?>

<div class="container py-5">
    <!-- Message de notification -->
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fa fa-check-circle me-2"></i> L'épreuve a été mise à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 p-4 bg-white rounded shadow-sm border-start border-primary border-5">
        <div>
            <h3 class="text-primary m-0 fw-bold"><i class="fa fa-file-pdf me-2"></i>Gestion des Épreuves</h3>
            <p class="text-muted small mb-0">Liste des sujets et corrigés PDF uploadés</p>
        </div>
        <a href="epreuves_add.php" class="btn btn-primary px-4 py-2 shadow-sm">
            <i class="fa fa-upload me-2"></i>Nouvel Upload
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 py-3 text-primary">Année / Titre</th>
                        <th class="border-0 py-3 text-primary">Matière</th>
                        <th class="border-0 py-3 text-center text-primary">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($epreuves) > 0): ?>
                        <?php foreach($epreuves as $e): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-danger rounded p-2 me-3">
                                        <i class="fa fa-file-pdf fa-lg"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($e['annee']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(6, 187, 204, 0.1); color: #06BBCC; border: 1px solid #06BBCC;">
                                    <i class="fa fa-book me-1"></i> <?= htmlspecialchars($e['nom_matiere']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="epreuves_edit.php?id=<?= $e['id_epreuve'] ?>" class="btn btn-outline-dark btn-sm me-2" title="Modifier">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="epreuves_delete.php?id=<?= $e['id_epreuve'] ?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       onclick="return confirm('Êtes-vous sûre de vouloir supprimer ce fichier PDF ?')" 
                                       title="Supprimer">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fa fa-folder-open fa-3x text-light d-block mb-3"></i>
                                <span class="text-muted">Aucune épreuve trouvée. Commencez par en ajouter une !</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>