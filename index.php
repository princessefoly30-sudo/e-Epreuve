<?php 
require_once 'include/db.php'; 
include 'include/header.php'; 

// Remplace ta requête actuelle par celle-ci (on ajoute session_type)
$query = "SELECT 
            e.id_epreuve, 
            e.annee, 
            e.session_type, 
            e.fichier_pdf, 
            m.nom_matiere, 
            f.nom_filiere, 
            form.nom_formation 
          FROM epreuve e 
          JOIN matiere m ON e.id_matiere = m.id_matiere 
          JOIN filiere f ON m.id_filiere = f.id_filiere 
          JOIN formation form ON f.id_formation = form.id_formation 
          ORDER BY e.id_epreuve DESC";
          
$stmt = $pdo->query($query);
$toutes_epreuves = $stmt->fetchAll();

$recentes_ids = array_column(array_slice($toutes_epreuves, 0, 3), 'id_epreuve');
?>

<style>
    :root {
        --primary-color: #06BBCC;
        --secondary-color: #181d38;
        --accent-color: #ff4757;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    body { background-color: #f0f4f8; }

    .hero-header {
        background: linear-gradient(rgba(24, 29, 56, .8), rgba(24, 29, 56, .8)), url('img/accueil_background.jpg');
        background-position: center;
        background-size: cover;
        padding: 120px 0 80px 0;
        clip-path: ellipse(150% 100% at 50% 0%);
    }

    .search-box-wrapper {
        margin-top: -50px;
        z-index: 10;
        position: relative;
    }
    .search-container {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: 0.3s;
    }
    .search-container:focus-within {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(6, 187, 204, 0.2) !important;
    }

    .human-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0,0,0,0.03);
    }
    .human-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
    }

    .card-image-box {
        height: 180px;
        position: relative;
    }
    .card-image-box img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .overlay-filiere {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(24, 29, 56, 0.8);
        backdrop-filter: blur(5px);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-new-glow {
        position: absolute; top: 15px; right: 15px;
        background: var(--accent-color);
        color: white; padding: 5px 15px;
        border-radius: 50px; font-size: 0.65rem; font-weight: 800;
        letter-spacing: 1px;
        box-shadow: 0 0 15px rgba(255, 71, 87, 0.5);
    }

    .btn-filter {
        background: white;
        border: 1px solid #dee2e6;
        color: var(--secondary-color);
        transition: 0.3s;
        font-weight: 500;
    }
    .btn-filter:hover, .btn-filter.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(6, 187, 204, 0.3);
    }

    .btn-premium {
        background: linear-gradient(45deg, var(--primary-color), #05a8b8);
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s;
        border: none;
        width: 100%;
        display: inline-block;
        text-align: center;
    }
    .btn-premium:hover {
        color: white;
        filter: brightness(1.1);
        box-shadow: 0 5px 15px rgba(6, 187, 204, 0.4);
    }

    .icon-circle {
        width: 40px; height: 40px;
        background: rgba(6, 187, 204, 0.1);
        color: var(--primary-color);
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
    }
</style>

<div class="container-fluid hero-header mb-5">
    <div class="container text-center py-5">
        <h5 class="text-primary text-uppercase mb-3 animated slideInDown" style="letter-spacing: 5px;">E-Library NVADIGITAL</h5>
        <h1 class="display-2 text-white fw-bold animated slideInDown mb-4">Réussissez vos <span class="text-primary text-decoration-underline">Examens</span></h1>
        <p class="text-white-50 fs-5 mb-4 mx-auto animated fadeInUp" style="max-width: 700px;">
            Accédez instantanément à des milliers d'annales, sujets et corrigés organisés par filières.
        </p>
    </div>
</div>

<div class="container">
    <!-- Recherche -->
    <div class="row justify-content-center search-box-wrapper mb-5">
        <div class="col-md-9 col-lg-7">
            <div class="search-container shadow-lg rounded-pill bg-white p-2 d-flex align-items-center">
                <div class="icon-circle ms-2"><i class="fa fa-search"></i></div>
                <input type="text" id="RechercheLive" onkeyup="filterCards()" 
                       placeholder="Matière, Année ou Filière..." 
                       class="form-control border-0 py-3 ps-3 rounded-pill shadow-none">
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="text-center mb-5">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <button class="btn btn-filter active rounded-pill px-4 py-2" onclick="quickSearch('', this)">Tout explorer</button>
            <button class="btn btn-filter rounded-pill px-4 py-2" onclick="quickSearch('PHP', this)">Développement PHP</button>
            <button class="btn btn-filter rounded-pill px-4 py-2" onclick="quickSearch('Comptabilité', this)">Comptabilité</button>
            <button class="btn btn-filter rounded-pill px-4 py-2" onclick="quickSearch('2024', this)">Session 2024</button>
            <!-- Dans ta section "Filtres", ajoute ce bouton après celui de 2024 -->
<button class="btn btn-filter rounded-pill px-4 py-2" onclick="quickSearch('Rattrapage', this)">Rattrapages</button>
        </div>
    </div>

    <!-- Grille corrigée -->
    <div class="row g-4" id="epreuveGrid">
        <?php foreach($toutes_epreuves as $ep): ?>
        <div class="col-lg-4 col-md-6 epreuve-card-item animated fadeInUp">
            <div class="human-card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-image-box">
                    <img src="img/standard_exam.jpg" alt="Examen">
                    <div class="overlay-filiere">
                        <i class="fa fa-tag me-1"></i> <?= htmlspecialchars($ep['nom_filiere']) ?>
                    </div>
                    <?php if(in_array($ep['id_epreuve'], $recentes_ids)): ?>
                        <div class="badge-new-glow animated pulse infinite">NEW</div>
                    <?php endif; ?>
                </div>

                <div class="p-4 flex-grow-1 d-flex flex-column">
                    <div class="mb-3">
                        <span class="badge bg-soft-primary text-primary border-0 rounded-pill px-3 py-2 small" style="background: rgba(6, 187, 204, 0.1);">
                            <i class="fa fa-graduation-cap me-1"></i> <?= htmlspecialchars($ep['nom_formation']) ?>
                        </span>
                    </div>
<!-- Titre de la Session -->
<h4 class="fw-bold text-secondary mb-3">
    Session <?= htmlspecialchars($ep['annee'] ?? 'Non précisée') ?> 
    
    <?php if(!empty($ep['session_type'])): ?>
        <small class="text-muted fw-light" style="font-size: 0.9rem;">
            (<?= htmlspecialchars($ep['session_type']) ?>)
        </small>
    <?php endif; ?>
</h4>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle me-3"><i class="fa fa-book-open small"></i></div>
                        <div>
                            <p class="text-muted mb-0 small">Matière</p>
                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($ep['nom_matiere']) ?></h6>
                        </div>
                    </div>

                    <div class="mt-auto">
                       <!-- Dans ton bouton Action -->
<a href="uploads/<?= $ep['fichier_pdf'] ?>" target="_blank" class="btn-premium text-decoration-none text-white">
    <i class="fa fa-file-pdf me-2"></i> VOIR LE SUJET
</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="noResult" class="text-center py-5 d-none">
        <div class="bg-white d-inline-block p-5 rounded-circle shadow-sm mb-4">
            <i class="fa fa-search-minus fa-4x text-muted"></i>
        </div>
        <h3 class="text-secondary fw-bold">Oups ! Aucun résultat</h3>
        <p class="text-muted">Essayez d'autres mots-clés.</p>
    </div>
</div>

<script>
function filterCards() {
    let input = document.getElementById('RechercheLive').value.toLowerCase();
    let cards = document.getElementsByClassName('epreuve-card-item');
    let noResult = document.getElementById('noResult');
    let visibleCount = 0;

    for (let i = 0; i < cards.length; i++) {
        let content = cards[i].textContent.toLowerCase();
        if (content.includes(input)) {
            cards[i].style.display = "";
            visibleCount++;
        } else {
            cards[i].style.display = "none";
        }
    }
    noResult.classList.toggle('d-none', visibleCount > 0);
}

function quickSearch(val, btn) {
    document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('RechercheLive').value = val;
    filterCards();
}
</script>

<?php include 'include/footer.php'; ?>