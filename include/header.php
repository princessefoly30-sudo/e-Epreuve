<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Epreuve - NVADIGITAL</title>

   

    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/Gestion_epreuve/css/bootstrap.min.css">

    <link rel="stylesheet" href="/Gestion_epreuve/css/style.css">
    <style>
        :root { --primary: #06BBCC; --dark: #181d38; }
        .navbar-brand { font-weight: bold; }
        .text-primary { color: var(--primary) !important; }
        .btn-logout { 
            background: var(--dark); color: white !important; 
            font-weight: bold; padding: 8px 20px; border-radius: 5px;
            text-decoration: none; margin-left: 15px;
        }
        .btn-login {
            background: var(--primary); color: white !important;
            padding: 8px 25px; border-radius: 5px; font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="/Gestion_epreuve/index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>e-Epreuve</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0 align-items-center">
                <a href="/Gestion_epreuve/index.php" class="nav-item nav-link">Accueil</a>
                
                <?php 
                // Correction de la variable : on utilise 'admin_logged' comme dans ton login.php
                if(isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true): 
                ?>
                    <!-- Menu Administration (Visible uniquement si connecté) -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Administration</a>
                        <div class="dropdown-menu fade-down m-0 shadow-sm border-0">
                            <a href="/Gestion_epreuve/admin/formations_list.php" class="dropdown-item">Niveaux d'étude</a>
                            <a href="/Gestion_epreuve/admin/filieres_list.php" class="dropdown-item">Filières</a>
                            <a href="/Gestion_epreuve/admin/matieres_list.php" class="dropdown-item">Matières</a>
                            <a href="/Gestion_epreuve/admin/epreuves_list.php" class="dropdown-item">Épreuves</a>
                        </div>
                    </div>
                    
                    <!-- Bouton Déconnexion -->
                    <a href="/Gestion_epreuve/admin/logout.php" class="btn-logout">Quitter</a>

                <?php else: ?>
                    <!-- Bouton Connexion (Visible si non connecté) -->
                    <a href="/Gestion_epreuve/admin/login.php" class="nav-item nav-link btn-login ms-lg-3">Connexion Admin</a>
                <?php endif; ?>

            </div>
        </div>
    </nav>

    <!-- Inclusion de Bootstrap JS (Important pour les menus déroulants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>