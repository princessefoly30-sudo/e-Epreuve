<?php 
include 'include/db.php'; 
include 'include/header.php'; 

// 1. On récupère l'ID de la matière
$id_mat = isset($_GET['id_mat']) ? $_GET['id_mat'] : null;

if ($id_mat) {
    // 2. On récupère le nom de la matière et les infos parentes pour le titre
    $req = $pdo->prepare("
        SELECT m.nom_matiere, f.nom_filiere, fo.nom_formation 
        FROM matiere m
        JOIN filiere f ON m.id_filiere = f.id_filiere
        JOIN formation fo ON f.id_formation = fo.id_formation
        WHERE m.id_matiere = ?");
    $req->execute([$id_mat]);
    $infos = $req->fetch();

    // 3. On récupère les épreuves de cette matière
    $req_epreuve = $pdo->prepare("SELECT * FROM epreuve WHERE id_matiere = ? ORDER BY annee DESC");
    $req_epreuve->execute([$id_mat]);
    $epreuves = $req_epreuve->fetchAll();
} else {
    header("Location: index.php");
    exit();
}
?>

<div class="container-fluid bg-primary py-5 mb-5 page-header" style="background: linear-gradient(rgba(24, 29, 56, .7), rgba(24, 29, 56, .7)), url('img/header-epreuve.jpg'); background-size: cover;">
    <div class="container py-5 text-center">
        <h1 class="display-3 text-white animated slideInDown">Épreuves : <?= htmlspecialchars($infos['nom_matiere']) ?></h1>
        <p class="text-white fw-bold"><?= $infos['nom_formation'] ?> | <?= $infos['nom_filiere'] ?></p>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <?php if (count($epreuves) > 0): ?>
                <?php foreach($epreuves as $e): ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card border-0 shadow-sm rounded overflow-hidden h-100">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <i class="fa fa-file-pdf fa-4x text-danger"></i>
                            </div>
                            <h5 class="mb-2">Session <?= htmlspecialchars($e['annee']) ?></h5>
                            <p class="text-muted small">Format : PDF</p>
                            
                            <a href="uploads/<?= $e['fichier_pdf'] ?>" target="_blank" class="btn btn-primary rounded-pill px-4 mt-3">
                                <i class="fa fa-download me-2"></i>Télécharger
                            </a>
                        </div>
                        <div class="card-footer bg-light border-0 text-center py-2">
                             <small class="text-primary fw-bold">Nvadigital - e-Epreuve</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-light p-5 rounded wow zoomIn">
                        <i class="fa fa-folder-open fa-3x text-primary mb-3"></i>
                        <h4>Oups ! Aucune épreuve disponible.</h4>
                        <p>Nous n'avons pas encore d'archives pour cette matière. Reviens bientôt !</p>
                        <a href="index.php" class="btn btn-primary rounded-pill px-5">Retour à l'accueil</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>