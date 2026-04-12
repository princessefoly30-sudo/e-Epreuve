<?php 
include '../include/db.php'; 
include '../include/header.php'; 

// TRAITEMENT DU FORMULAIRE
if (isset($_POST['save'])) {
    $nom = $_POST['nom'];
    $maire = $_POST['maire'];
    $pop = $_POST['pop'];
    $type = $_POST['type'];
    $id_dept = $_POST['id_departement'];

    // Gestion de l'image
    $nom_final = ""; // Valeur par défaut si pas d'image
    if (!empty($_FILES['img']['name'])) {
        $image = $_FILES['img'];
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $nom_final = uniqid() . "." . $extension;
        $destination = "../uploads/" . $nom_final;
        move_uploaded_file($image['tmp_name'], $destination);
    }

    // Insertion avec PDO
    $sql = "INSERT INTO communes (nom_commune, maire, population, type_commune, image_commune, id_departement) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $maire, $pop, $type, $nom_final, $id_dept]);
    
    header("Location: list.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0">Ajouter une Commune</h4>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label">Nom de la commune</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Maire</label>
                            <input type="text" name="maire" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Population</label>
                            <input type="number" name="pop" class="form-control" placeholder="Ex: 679000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="Particulier">Particulier</option>
                                <option value="Intermédiaire">Intermédiaire</option>
                                <option value="Ordinaire">Ordinaire</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Département</label>
                            <select name="id_departement" class="form-select" required>
                                <option value="">-- Choix --</option>
                                <?php
                                // Utilisation de PDO pour la liste déroulante
                                $query = $pdo->query("SELECT * FROM departement");
                                while ($dept = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='".$dept['id_departement']."'>".$dept['nom_departement']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image de la commune</label>
                            <input type="file" name="img" class="form-control">
                        </div>

                        <button name="save" type="submit" class="btn btn-dark w-100 py-2">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>