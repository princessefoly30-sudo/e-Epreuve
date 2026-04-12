<?php
require_once 'include/db.php';

if (isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";
    
    // Requête pour chercher partout (matière, épreuve, filière)
    $stmt = $pdo->prepare("SELECT e.*, m.nom_matiere, f.nom_filiere 
                           FROM epreuve e 
                           JOIN matiere m ON e.id_matiere = m.id_matiere 
                           JOIN filiere f ON m.id_filiere = f.id_filiere 
                           WHERE e.annee LIKE ? 
                           OR m.nom_matiere LIKE ? 
                           OR f.nom_filiere LIKE ? 
                           LIMIT 10");
    $stmt->execute([$q, $q, $q]);
    $results = $stmt->fetchAll();

    if ($results) {
        foreach ($results as $res) {
            echo '<a href="direct_view.php?id=' . $res['id_epreuve'] . '" class="list-group-item list-group-item-action">';
            echo '  <div class="d-flex w-100 justify-content-between">';
            echo '    <h6 class="mb-1">' . htmlspecialchars($res['annee']) . '</h6>';
            echo '    <small class="text-primary">' . htmlspecialchars($res['nom_filiere']) . '</small>';
            echo '  </div>';
            echo '  <small class="text-muted">Matière: ' . htmlspecialchars($res['nom_matiere']) . '</small>';
            echo '</a>';
        }
    } else {
        echo '<div class="list-group-item text-muted">Aucune épreuve trouvée...</div>';
    }
}