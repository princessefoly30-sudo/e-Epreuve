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

// On récupère la formation à modifier via l'ID dans l'URL
$id = $_GET['id'];
$get_f = $pdo->prepare("SELECT * FROM formation WHERE id_formation = ?");
$get_f->execute([$id]);
$formation = $get_f->fetch();

if (isset($_POST['modifier'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $upd = $pdo->prepare("UPDATE formation SET nom_formation = ? WHERE id_formation = ?");
    $upd->execute([$nom, $id]);
    header("Location: formations_list.php");
    exit();
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Modifier la Formation</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nouveau Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= $formation['nom_formation'] ?>" required>
                        </div>
                        <button type="submit" name="modifier" class="btn btn-primary text-white w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>