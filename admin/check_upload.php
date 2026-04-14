<?php
// 1. Démarrage de la session
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. Sécurité : Vérification de l'accès admin
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header('Location: login.php');
    exit();
}

require_once '../include/db.php'; 
include '../include/header.php'; 

// Récupérer les limites PHP
$upload_max = ini_get('upload_max_filesize');
$post_max = ini_get('post_max_size');
$memory = ini_get('memory_limit');
$max_execution = ini_get('max_execution_time');

// Convertir en bytes pour faire les calculs
function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int)$value;
    
    switch($last) {
        case 'g': $value *= 1024;
        case 'm': $value *= 1024;
        case 'k': $value *= 1024;
    }
    return $value;
}

$upload_bytes = convertToBytes($upload_max);
$post_bytes = convertToBytes($post_max);

// Vérifier les permissions du dossier uploads
$uploads_dir = '../uploads/';
$is_writable = is_writable($uploads_dir);
$dir_perms = substr(sprintf('%o', fileperms($uploads_dir)), -4);

// Vérifier l'espace disque
$free_space = disk_free_space($uploads_dir);
?>

<div class="container py-5">
    <div class="card shadow border-0">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0"><i class="fa fa-cogs me-2"></i>Diagnostic d'Upload</h4>
        </div>
        <div class="card-body p-4">
            
            <!-- LIMITES PHP -->
            <div class="row mb-5">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">⚙️ Limites PHP</h5>
                    <table class="table table-borderless small">
                        <tr>
                            <td><strong>Max Upload Size:</strong></td>
                            <td class="text-end text-primary"><?= $upload_max ?></td>
                        </tr>
                        <tr>
                            <td><strong>Max POST Size:</strong></td>
                            <td class="text-end text-primary"><?= $post_max ?></td>
                        </tr>
                        <tr>
                            <td><strong>Memory Limit:</strong></td>
                            <td class="text-end text-primary"><?= $memory ?></td>
                        </tr>
                        <tr>
                            <td><strong>Max Execution Time:</strong></td>
                            <td class="text-end text-primary"><?= $max_execution ?> sec</td>
                        </tr>
                    </table>
                </div>
                
                <!-- DOSSIER UPLOADS -->
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">📁 Dossier uploads/</h5>
                    <table class="table table-borderless small">
                        <tr>
                            <td><strong>Chemin:</strong></td>
                            <td class="text-end"><?= realpath($uploads_dir) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Inscriptible :</strong></td>
                            <td class="text-end">
                                <?php if($is_writable): ?>
                                    <span class="badge bg-success">✓ OUI</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ NON</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Permissions:</strong></td>
                            <td class="text-end text-primary"><?= $dir_perms ?></td>
                        </tr>
                        <tr>
                            <td><strong>Espace libre:</strong></td>
                            <td class="text-end text-primary"><?= round($free_space / (1024*1024*1024), 2) ?> GB</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- FICHIERS ACTUELS -->
            <h5 class="fw-bold mb-3">📄 Fichiers dans uploads/</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom du fichier</th>
                            <th class="text-end">Taille</th>
                            <th class="text-end">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $files = scandir($uploads_dir);
                        foreach($files as $file) {
                            if($file !== '.' && $file !== '..') {
                                $filepath = $uploads_dir . $file;
                                $size = filesize($filepath);
                                $date = date('d/m/Y H:i', filemtime($filepath));
                                echo "<tr>
                                    <td>$file</td>
                                    <td class='text-end'>" . round($size / (1024*1024), 2) . " MB</td>
                                    <td class='text-end'>$date</td>
                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- RECOMMANDATIONS -->
            <hr>
            <h5 class="fw-bold mb-3">✅ Recommandations</h5>
            <div class="alert alert-light border">
                <ul class="mb-0 small">
                    <?php if($upload_bytes < 50000000): ?>
                        <li>⚠️ <strong>upload_max_filesize</strong> est à <?= $upload_max ?> (moins de 50 MB). Augmentez-la à 50M dans php.ini</li>
                    <?php endif; ?>
                    
                    <?php if($post_bytes < 50000000): ?>
                        <li>⚠️ <strong>post_max_size</strong> est à <?= $post_max ?> (moins de 50 MB). Augmentez-la à 50M dans php.ini</li>
                    <?php endif; ?>
                    
                    <?php if(!$is_writable): ?>
                        <li>🔴 <strong>CRITIQUE</strong> : Le dossier uploads/ n'est pas inscriptible ! Changez les permissions en 755 ou 777</li>
                    <?php else: ?>
                        <li>✅ Le dossier uploads/ est inscriptible</li>
                    <?php endif; ?>
                    
                    <?php if($free_space < 100000000): ?>
                        <li>⚠️ Espace disque faible (< 100 MB). Libérez de l'espace avant d'uploader</li>
                    <?php else: ?>
                        <li>✅ Espace disque suffisant</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="mt-4">
                <a href="epreuves_add.php" class="btn btn-primary">← Retour à l'upload</a>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
