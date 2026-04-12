<?php 
include '../include/db.php'; 
include '../include/header.php'; 

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM communes WHERE id_commune = ?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (isset($_POST['update'])) {
    $nom = $_POST['nom'];
    $maire = $_POST['maire'];
    $pop = $_POST['pop'];
    $type = $_POST['type'];
    
    // Par défaut, on garde l'ancienne image si on n'en choisit pas une nouvelle
    $image_nom = $c['image_commune'];

    // Si une nouvelle image est téléchargée
    if (!empty($_FILES['img']['name'])) {
        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $image_nom = uniqid() . "." . $extension; // On crée un nom unique
        move_uploaded_file($_FILES['img']['tmp_name'], "../uploads/" . $image_nom);
    }

    $sql = "UPDATE communes SET nom_commune=?, maire=?, population=?, type_commune=?, image_commune=? WHERE id_commune=?";
    $pdo->prepare($sql)->execute([$nom, $maire, $pop, $type, $image_nom, $id]);
   
    header("Location: list.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 card shadow-lg border-0 bg text-dark">
            <div class="card-header texte  --bs-blue border-secondary text-center py-3">
                <h4 class="mb-0 ">Modifier</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data">
                    <label class="form-label small text-secondary">Nom de la commune</label>
                    <input type="text warning" name="nom" value="<?= $c['nom_commune'] ?>" class="form-control bg-secondary text-white border-0 mb-3">
                    <label class="form-label small text-secondary">Maire actuel</label>
                    <input type="text" name="maire" value="<?= $c['maire'] ?>" class="form-control bg-secondary text-white border-0 mb-3">
                    
                    <label class="form-label small text-secondary">Population</label>
                    <input type="number" name="pop" value="<?= $c['population'] ?>" class="form-control bg-secondary text-white border-0 mb-3">
                    
                    <label class="form-label small text-secondary">Type de commune</label>
                    <select name="type" class="form-select bg-secondary text-white border-0 mb-3">
                        <option <?= $c['type_commune'] == 'Particulier' ? 'selected' : '' ?>>Particulier</option>
                        <option <?= $c['type_commune'] == 'Intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                        <option <?= $c['type_commune'] == 'Ordinaire' ? 'selected' : '' ?>>Ordinaire</option>
                    </select>
                    <label class="form-label small text-secondary">Département</label>
                    <select name="id_departement" class="form-select bg-secondary text-white border-0 mb-3">
                        <option value="">-- Choix --</option>
                        <?php
                        $query = $pdo->query("SELECT * FROM departement");
                        while ($dept = $query->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($c['id_departement'] == $dept['id_departement']) ? 'selected' : '';/
                            echo "<option value='".$dept['id_departement']."' $selected>".$dept['nom_departement']."</option>";
                        }
                        ?>
                    </select>

                    <label class="form-label small text-secondary">Nouvelle Image (optionnel)</label>
                    <input type="file" name="img" class="form-control bg-secondary text-white border-0 mb-4">
                    
                    <div class="text-center mb-3">
                        <p class="small text-secondary">Image actuelle :</p>
                        <img src="../uploads/<?= $c['image_commune'] ?>" width="80" class="rounded border border-secondary">
                    </div>

                    <div class="d-flex gap-2">
                        <button name="update" class="btn btn-dark w-100 ">Enregistrer les modifs</button>
                        <a href="list.php" class="btn btn-dark w-100">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>