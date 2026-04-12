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


// 1. On vérifie si l'ID est présent dans l'URL
if (!isset($_GET['id'])) {
    header("Location: filieres_list.php");
    exit();
}

$id = $_GET['id'];

// 2. On récupère les données de la filière à modifier
$get_filiere = $pdo->prepare("SELECT * FROM filiere WHERE id_filiere = ?");
$get_filiere->execute([$id]);
$filiere = $get_filiere->fetch();

// 3. On récupère la liste des formations pour le menu déroulant
$formations = $pdo->query("SELECT * FROM formation")->fetchAll();

// 4. Traitement de la modification après validation du formulaire
if (isset($_POST['modifier'])) {
    $nom = htmlspecialchars($_POST['nom_filiere']);
    $id_form = $_POST['id_formation'];

    $update = $pdo->prepare("UPDATE filiere SET nom_filiere = ?, id_formation = ? WHERE id_filiere = ?");
    $update->execute([$nom, $id_form, $id]);
    
    // Petite redirection vers la liste avec un message de succès (optionnel)
    echo "<script>window.location.href='filieres_list.php?msg=Modifié';</script>";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white"><i class="fa fa-edit me-2"></i>Modifier la Filière</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nom de la Filière</label>
                            <input type="text" name="nom_filiere" class="form-control" 
                                   value="<?= $filiere['nom_filiere'] ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Rattacher au Niveau (Formation)</label>
                            <select name="id_formation" class="form-select" required>
                                <?php foreach($formations as $fo): ?>
                                    <option value="<?= $fo['id_formation'] ?>" 
                                        <?= ($fo['id_formation'] == $filiere['id_formation']) ? 'selected' : '' ?>>
                                        <?= $fo['nom_formation'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="modifier" class="btn btn-primary text-white w-100">
                                Mettre à jour
                            </button>
                            <a href="filieres_list.php" class="btn btn-secondary w-100">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>