<?php 
include '../include/db.php'; 
include '../include/header.php'; 

// Gestion de la suppression
if (isset($_GET['del'])) {
    $stmt = $pdo->prepare("DELETE FROM communes WHERE id_commune = ?");
    $stmt->execute([$_GET['del']]);
    header("Location: list.php");
    exit();
}

$communes = $pdo->query("SELECT * FROM communes")->fetchAll();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark"><i class="fa fa-city me-2"></i>Liste des Communes</h2>
        <a href="add.php" class="btn btn-dark"><i class="fa fa-plus me-1"></i> Ajouter</a>
    </div>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle bg-white mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Maire</th>
                    <th>Population</th>
                    <th>Type</th>
                    <th>Département</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($communes as $c): ?>
                <tr>
                    <td>
                        <?php if(!empty($c['image_commune'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($c['image_commune']) ?>" 
                                 alt="Image" class="rounded" 
                                 style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #ddd;">
                        <?php else: ?>
                            <div class="bg-light text-center rounded" style="width: 60px; height: 60px; line-height: 60px;">
                                <i class="fa fa-image text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    
                    <td class="fw-bold"><?= htmlspecialchars($c['nom_commune']) ?></td>
                    <td><?= htmlspecialchars($c['maire']) ?></td>
                    <td><?= number_format($c['population'], 0, ',', ' ') ?> hab.</td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($c['type_commune']) ?></span></td>
                    <td>
                        <?php 
                        $dept_stmt = $pdo->prepare("SELECT nom_departement FROM departement WHERE id_departement = ?");
                        $dept_stmt->execute([$c['id_departement']]);
                        $dept = $dept_stmt->fetch();
                        echo htmlspecialchars($dept['nom_departement']);
                        ?>
                    
                    <td class="text-center">
                        <a href="edit.php?id=<?= $c['id_commune'] ?>" class="btn btn-sm outline"><i class="fa fa-edit"></i></a>
                        <a href="list.php?del=<?= $c['id_commune'] ?>" class="btn btn-sm btn-outline" onclick="return confirm('Supprimer ?')"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../include/footer.php'; ?>