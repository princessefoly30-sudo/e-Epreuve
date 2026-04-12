<?php 
require_once 'include/db.php'; 
include 'include/header.php'; 

// Récupération des statistiques réelles
$total_epreuves = $pdo->query("SELECT COUNT(*) FROM epreuve")->fetchColumn();
$total_matieres = $pdo->query("SELECT COUNT(*) FROM matiere")->fetchColumn();
$total_filieres = $pdo->query("SELECT COUNT(*) FROM filiere")->fetchColumn();
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Statistiques</h6>
            <h1 class="mb-5">Tableau de Bord Nvadigital</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item text-center pt-3 shadow">
                    <div class="p-4">
                        <i class="fa fa-3x fa-file-pdf text-primary mb-4"></i>
                        <h5 class="mb-3">Épreuves</h5>
                        <h2 class="display-4 text-dark"><?= $total_epreuves ?></h2>
                        <p>Archives disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item text-center pt-3 shadow">
                    <div class="p-4">
                        <i class="fa fa-3x fa-book-open text-primary mb-4"></i>
                        <h5 class="mb-3">Matières</h5>
                        <h2 class="display-4 text-dark"><?= $total_matieres ?></h2>
                        <p>Disciplines couvertes</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="service-item text-center pt-3 shadow">
                    <div class="p-4">
                        <i class="fa fa-3x fa-graduation-cap text-primary mb-4"></i>
                        <h5 class="mb-3">Filières</h5>
                        <h2 class="display-4 text-dark"><?= $total_filieres ?></h2>
                        <p>Parcours de formation</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-primary py-3 px-5 mt-2 rounded-pill">Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>