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


// Requête SQL avec jointure pour afficher la filière
$sql = "SELECT m.*, f.nom_filiere 
        FROM matiere m 
        LEFT JOIN filiere f ON m.id_filiere = f.id_filiere 
        ORDER BY m.id_matiere DESC";

$matieres = $pdo->query($sql)->fetchAll();
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
            <div>
                <h6 class="section-title bg-white text-start text-primary px-3">Administration</h6>
                <h1 class="mb-0">Liste des Matières</h1>
            </div>
            <a href="matieres_add.php" class="btn btn-primary py-3 px-5 animated slideInRight">
                <i class="fa fa-plus-circle me-2"></i>Nouvelle Matière
            </a>
        </div>

        <div class="card shadow border-0 wow fadeInUp" data-wow-delay="0.3s" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Nom de la Matière</th>
                            <th class="py-3">Filière Associée</th>
                            <th class="text-center py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($matieres) > 0): ?>
                            <?php foreach($matieres as $m): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $m['id_matiere'] ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($m['nom_matiere']) ?></span>
                                </td>
                                <td>
                                    <?php if($m['nom_filiere']): ?>
                                        <span class="badge px-3 py-2" style="background-color: rgba(6, 187, 204, 0.1); color: var(--primary); border: 1px solid var(--primary);">
                                            <i class="fa fa-tag me-1"></i> <?= htmlspecialchars($m['nom_filiere']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Non classée</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="matieres_edit.php?id=<?= $m['id_matiere'] ?>" class="btn btn-sm btn-outline-dark" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="matieres_delete.php?id=<?= $m['id_matiere'] ?>" 
                                           class="btn btn-sm btn-outline-dark" 
                                           onclick="return confirm('Voulez-vous vraiment supprimer cette matière ?')" 
                                           title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa fa-folder-open fa-3x mb-3 d-block text-primary"></i>
                                    Aucune matière n'a été trouvée.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Harmonisation avec ton template e-Epreuve */
    :root {
        --primary: #06BBCC;
    }
    .btn-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
    .section-title::after {
        position: absolute;
        content: "";
        width: 45px;
        height: 2px;
        top: 50%;
        right: -55px;
        margin-top: -1px;
        background: var(--primary);
    }
    .table thead th {
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
</style>

<?php include '../include/footer.php'; ?>