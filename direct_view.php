<?php 
require_once 'include/db.php'; 
include 'include/header.php'; 

// 1. On récupère l'ID envoyé par l'URL
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // 2. On cherche les infos de cette épreuve précise avec une requête préparée
    $stmt = $pdo->prepare("SELECT e.*, m.nom_matiere FROM epreuve e 
                           JOIN matiere m ON e.id_matiere = m.id_matiere 
                           WHERE e.id_epreuve = ?");
    $stmt->execute([$id]);
    $epreuve = $stmt->fetch();

    if(!$epreuve) {
        echo "<div class='container py-5 text-center'><h3>Épreuve introuvable.</h3><a href='index.php' class='btn btn-primary mt-3'>Retour</a></div>";
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<div class="container-xxl py-5" style="background-color: #f4f7f9; min-height: 90vh;">
    <div class="container">
        <a href="index.php" class="btn btn-outline-primary rounded-pill mb-4 wow fadeInLeft" data-wow-delay="0.1s">
            <i class="fa fa-arrow-left me-2"></i> Retour aux archives
        </a>

        <div class="row g-4">
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                <div class="bg-white p-4 shadow-sm h-100 border-0" style="border-radius: 25px;">
                    <div class="text-center mb-4">
                        <div class="icon-circle bg-light p-3 d-inline-block rounded-circle mb-3">
                            <i class="fa fa-file-pdf fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($epreuve['annee']) ?></h4>
                        <span class="badge bg-soft-primary text-primary mt-2">Document Officiel</span>
                    </div>
                    
                    <hr class="text-muted opacity-25">
                    
                    <div class="info-item mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Matière</small>
                        <p class="mb-0 fw-bold"><?= htmlspecialchars($epreuve['nom_matiere']) ?></p>
                    </div>

                    <p class="mb-4 text-muted small mt-4">
                        <i class="fa fa-info-circle me-1"></i> Cette archive est mise à disposition pour vous accompagner dans vos révisions. 
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="uploads/<?= htmlspecialchars($epreuve['fichier_pdf']) ?>" download class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm">
                            <i class="fa fa-download me-2"></i> Télécharger le PDF
                        </a>
                        <a href="uploads/<?= htmlspecialchars($epreuve['fichier_pdf']) ?>" target="_blank" class="btn btn-light rounded-pill py-2 text-muted small border">
                            <i class="fa fa-external-link-alt me-2"></i> Plein écran
                        </a>
                    </div>
                </div>
            </div>

           <div class="d-grid gap-2">
    <a href="uploads/<?= htmlspecialchars($epreuve['fichier_pdf']) ?>" 
       download 
       class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm">
        <i class="fa fa-download me-2"></i> Télécharger le PDF
    </a>
</div>

<div class="col-lg-8">
    <div class="bg-white p-2 shadow-sm" style="border-radius: 25px; height: 800px; overflow: hidden; border: 1px solid #eee;">
        <iframe src="uploads/<?= htmlspecialchars($epreuve['fichier_pdf']) ?>" 
                width="100%" 
                height="100%" 
                style="border: none; border-radius: 20px;">
        </iframe>
    </div>
</div>
                    </object>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Style complémentaire pour cette page */
    .bg-soft-primary { background-color: rgba(6, 187, 204, 0.1); }
    
    /* Animation douce pour le cercle d'icône */
    .icon-circle {
        transition: 0.3s;
    }
    .col-lg-4:hover .icon-circle {
        transform: scale(1.1) rotate(5deg);
    }
</style>

<?php include 'include/footer.php'; ?>