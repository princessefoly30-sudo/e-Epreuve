<?php 
include 'include/db.php'; 
include 'include/header.php'; 

// On récupère l'ID de la filière (ou de la formation selon ta structure)
$id_filiere = isset($_GET['id_filiere']) ? $_GET['id_filiere'] : null;

if ($id_filiere) {
    // 1. Récupérer le nom de la filière pour le titre
    $req_filiere = $pdo->prepare("SELECT nom_filiere FROM filiere WHERE id_filiere = ?");
    $req_filiere->execute([$id_filiere]);
    $filiere = $req_filiere->fetch();

    // 2. Récupérer les matières liées à cette filière
    $req_mat = $pdo->prepare("SELECT * FROM matiere WHERE id_filiere = ?");
    $req_mat->execute([$id_filiere]);
    $res = $req_mat->fetchAll();
} else {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<div class="container-fluid bg-primary py-5 mb-5 page-header" style="background: linear-gradient(rgba(24, 29, 56, .7), rgba(24, 29, 56, .7)), url('img/header-epreuve.jpg'); background-size: cover; background-position: center;">
    <div class="container py-5 text-center">
        <h1 class="display-3 text-white animated slideInDown"><?= htmlspecialchars($filiere['nom_filiere']) ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a class="text-white" href="index.php">Accueil</a></li>
                <li class="breadcrumb-item text-white active">Matières</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Étape 3</h6>
            <h1 class="mb-5">Matières au programme</h1>
        </div>

        <div class="row g-4">
            <?php if (count($res) > 0): ?>
                <?php foreach($res as $m): ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <a href="list_epreuve_view.php?id_mat=<?= $m['id_matiere'] ?>" class="text-decoration-none">
                        <div class="service-item rounded text-center h-100 p-4 border shadow-sm">
                            <div class="bg-light text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
                                <i class="fa fa-book-open fa-2x"></i>
                            </div>
                            <h5 class="mb-3 text-dark"><?= htmlspecialchars($m['nom_matiere']) ?></h5>
                            <p class="text-muted small mb-0">Cliquez pour voir les épreuves disponibles.</p>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center wow fadeInUp">
                    <div class="bg-light p-5 rounded">
                        <i class="fa fa-search fa-3x text-primary mb-3"></i>
                        <p class="text-muted">Aucune matière n'a été trouvée pour cette sélection.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>