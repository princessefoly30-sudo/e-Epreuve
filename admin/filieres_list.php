<?php 
// 1. Démarrer la session proprement
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. Vérifier la sécurité (AVANT TOUTE CHOSE)
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 3. Charger la base de données
require_once '../include/db.php'; 

// 4. Exécuter la requête SQL (On prépare les données avant d'afficher le HTML)
$sql = "SELECT f.*, fo.nom_formation 
        FROM filiere f 
        JOIN formation fo ON f.id_formation = fo.id_formation 
        ORDER BY f.id_filiere DESC";
$filieres = $pdo->query($sql)->fetchAll();

// 5. SEULEMENT MAINTENANT, on affiche le visuel
include '../include/header.php'; 
?>

<div class="container-xxl py-5">
    <div class="container">
        <!-- Petit message de succès optionnel si tu viens d'ajouter une filière -->
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa fa-check-circle me-2"></i> Opération réussie !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div>
                <h6 class="section-title bg-white text-start text-primary px-3">Administration</h6>
                <h1 class="mb-0">Gestion des Filières</h1>
            </div>
            <a href="filieres_add.php" class="btn btn-primary py-3 px-5 animated slideInRight shadow">
                <i class="fa fa-plus-circle me-2"></i>Nouvelle Filière
            </a>
        </div>

        <div class="card shadow border-0 wow fadeInUp" data-wow-delay="0.3s" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3">Nom de la Filière</th>
                            <th class="py-3">Niveau d'Étude</th>
                            <th class="text-center py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($filieres) > 0): ?>
                            <?php foreach($filieres as $fi): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($fi['nom_filiere']) ?></span>
                                </td>
                                <td>
                                    <span class="badge px-3 py-2" style="background-color: rgba(6, 187, 204, 0.1); color: #06BBCC; border: 1px solid #06BBCC;">
                                        <i class="fa fa-graduation-cap me-1"></i> <?= htmlspecialchars($fi['nom_formation']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="filieres_edit.php?id=<?= $fi['id_filiere'] ?>" class="btn btn-sm btn-outline-dark shadow-sm" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="filieres_delete.php?id=<?= $fi['id_filiere'] ?>" 
                                           class="btn btn-sm btn-outline-danger shadow-sm" 
                                           onclick="return confirm('Attention : Supprimer cette filière supprimera également les matières liées. Continuer ?')" 
                                           title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fa fa-university fa-3x mb-3 d-block text-primary"></i>
                                    Aucune filière enregistrée pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>