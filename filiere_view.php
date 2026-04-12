<?php 
require_once 'include/db.php'; 
include 'include/header.php'; 

$id_formation = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_formation) {
    $stmt = $pdo->prepare("SELECT nom_formation FROM formation WHERE id_formation = ?");
    $stmt->execute([$id_formation]);
    $formation = $stmt->fetch();

    $stmt_fil = $pdo->prepare("SELECT * FROM filiere WHERE id_formation = ?");
    $stmt_fil->execute([$id_formation]);
    $filieres = $stmt_fil->fetchAll();
} else {
    header("Location: index.php");
    exit();
}
?>

<div class="container-fluid bg-primary py-5 mb-5 page-header" style="background: linear-gradient(rgba(24, 29, 56, .8), rgba(24, 29, 56, .8)), url('img/header-epreuve.jpg'); background-size: cover; background-position: center;">
    <div class="container py-5 text-center">
        <h1 class="display-3 text-white animated slideInDown"><?= htmlspecialchars($formation['nom_formation']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a class="text-white" href="index.php">Accueil</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Filières</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Spécialités</h6>
            <h1 class="mb-5">Choisissez votre Filière</h1>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (count($filieres) > 0): ?>
                <?php foreach ($filieres as $f): ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="filiere-card position-relative shadow rounded overflow-hidden h-100 bg-light border-0">
                            <div class="p-5 text-center">
                                <div class="icon-box mb-4 mx-auto">
                                    <i class="fa fa-graduation-cap fa-3x text-primary"></i>
                                </div>
                                <h4 class="mb-3"><?= htmlspecialchars($f['nom_filiere']) ?></h4>
                                <p class="text-muted mb-4">Accédez aux ressources pédagogiques dédiées à la filière <?= htmlspecialchars($f['nom_filiere']) ?>.</p>
                                <a href="matiere_view.php?id_filiere=<?= $f['id_filiere'] ?>" class="btn btn-primary px-5 rounded-pill stretched-link shadow-sm">
                                    Voir les matières
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-6 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <div class="alert alert-light border shadow-sm p-5 rounded-3">
                        <i class="fa fa-folder-open fa-3x text-primary mb-3"></i>
                        <h5>Bientôt disponible !</h5>
                        <p class="text-muted">Aucune filière pour le niveau <?= htmlspecialchars($formation['nom_formation']) ?>.</p>
                        <a href="index.php" class="btn btn-outline-primary rounded-pill px-4 mt-3">Retourner à l'accueil</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .filiere-card {
        transition: all 0.5s ease;
        border-bottom: 5px solid transparent !important;
    }
    .filiere-card:hover {
        transform: translateY(-15px);
        border-bottom: 5px solid #06BBCC !important;
        background: #ffffff !important;
    }
    .icon-box {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0fbfc;
        border-radius: 50%;
        transition: 0.5s;
    }
    .filiere-card:hover .icon-box {
        background: #06BBCC;
    }
    .filiere-card:hover .icon-box i {
        color: white !important;
        transform: rotateY(180deg);
        transition: 0.8s;
    }
</style>

<?php include 'include/footer.php'?>
