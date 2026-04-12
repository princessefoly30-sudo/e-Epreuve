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
$id = $_GET['id'];
$matiere = $pdo->prepare("SELECT * FROM matiere WHERE id_matiere = ?");
$matiere->execute([$id]);
$m = $matiere->fetch();

$filieres = $pdo->query("SELECT * FROM filiere")->fetchAll();

if (isset($_POST['update'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $id_fil = $_POST['id_filiere'];
    $pdo->prepare("UPDATE matiere SET nom_matiere = ?, id_filiere = ? WHERE id_matiere = ?")->execute([$nom, $id_fil, $id]);
    header("Location: matieres_list.php");
}
?>

<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-header bg primary text-white">Modifier la Matière</div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom de la Matière</label>
                    <input type="text" name="nom" class="form-control" value="<?= $m['nom_matiere'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Changer de Filière</label>
                    <select name="id_filiere" class="form-select" required>
                        <?php foreach($filieres as $f): ?>
                            <option value="<?= $f['id_filiere'] ?>" <?= ($f['id_filiere'] == $m['id_filiere']) ? 'selected' : '' ?>>
                                <?= $f['nom_filiere'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="update" class="btn btn-primary text-white w-100">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
<?php include '../include/footer.php'; ?>