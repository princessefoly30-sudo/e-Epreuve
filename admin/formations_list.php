<?php 
// 1. Démarrage de la session en tout premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Vérification de la sécurité AVANT toute inclusion de HTML
// On utilise 'admin_logged' pour être cohérent avec ton système
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

// 3. Inclusions des fichiers de logique
require_once '../include/db.php'; 

// 4. Récupération des données
$formations = $pdo->query("SELECT * FROM formation ORDER BY id_formation DESC")->fetchAll();

// 5. ENFIN, on commence l'affichage avec le header
include '../include/header.php'; 
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div>
                <h6 class="section-title bg-white text-start text-primary px-3">Configuration</h6>
                <h1 class="mb-0">Niveaux d'Études</h1>
                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="alert alert-success">La formation a été ajoutée avec succès !</div>
<?php endif; ?>
            </div>
            <a href="formations_add.php" class="btn btn-primary py-3 px-5 animated slideInRight">
                <i class="fa fa-plus-circle me-2"></i>Ajouter un Niveau
            </a>
        </div>

        <div class="card shadow border-0 wow fadeInUp" data-wow-delay="0.3s" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3" style="width: 150px;">ID</th>
                            <th class="py-3">Nom de la Formation / Niveau</th>
                            <th class="text-center py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($formations) > 0): ?>
                            <?php foreach($formations as $f): ?>
                            <tr>
                                <td class="ps-4 text-muted">
                                    <span class="badge bg-light text-dark">#<?= $f['id_formation'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fa fa-graduation-cap small"></i>
                                        </div>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($f['nom_formation']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="formations_edit.php?id=<?= $f['id_formation'] ?>" class="btn btn-sm btn-outline-dark" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="formations_delete.php?id=<?= $f['id_formation'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Attention : Supprimer ce niveau supprimera peut-être les filières liées. Continuer ?')" 
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
                                    <i class="fa fa-list fa-3x mb-3 d-block text-primary"></i>
                                    Aucun niveau d'étude n'a été créé.
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