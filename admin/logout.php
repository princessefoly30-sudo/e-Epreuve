<?php
// 1. Démarrer la session pour pouvoir la manipuler
session_start();

// 2. Supprimer toutes les variables de session
$_SESSION = array();

// 3. Si on veut détruire complètement la session (y compris le cookie de session)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Détruire la session côté serveur
session_destroy();

// 5. Rediriger l'utilisateur vers la page de connexion ou l'accueil
// ../index.php renvoie à la racine de ton site e-Epreuve
header('Location: ../index.php');
exit();
?>