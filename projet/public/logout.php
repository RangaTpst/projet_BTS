<?php
// Démarrer la session pour accéder aux variables
session_start();

// Supprimer toutes les variables de session
$_SESSION = array();

// Si vous voulez détruire complètement la session, y compris les cookies de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Détruire la session
session_destroy();

// Supprimer les cookies de connexion (user_id, username, role)
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}
if (isset($_COOKIE['role'])) {
    setcookie('role', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}

// Rediriger vers la page de connexion ou d'accueil
header("Location: login.php");
exit();
