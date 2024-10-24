<?php
/**
 * Fichier logout.php
 * 
 * Ce fichier gère la déconnexion des utilisateurs.
 * Il détruit la session utilisateur, supprime les cookies de connexion, et redirige l'utilisateur vers la page de connexion.
 * 
 * Méthodes incluses :
 * - Suppression des variables de session.
 * - Destruction complète de la session (y compris les cookies de session).
 * - Suppression des cookies de connexion (user_id, username, role).
 * - Redirection vers la page de connexion après déconnexion.
 * 
 * PHP version 7.4+
 * 
 * @category   Authentication
 * @package    SR NAILS
 * @author     Nicolas <nicolas.rouillelanoe@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    1.0
 * @link       https://github.com/RangaTpst/projet_BTS
 */

// Démarrer la session pour accéder aux variables
session_start();

/**
 * Suppression de toutes les variables de session.
 * 
 * On réinitialise le tableau de session $_SESSION pour vider toutes les données stockées.
 */
$_SESSION = array();

/**
 * Destruction complète de la session (y compris les cookies de session).
 * 
 * Si la session utilise des cookies, ceux-ci sont également détruits en ajustant les paramètres
 * des cookies pour les rendre expirés.
 */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

/**
 * Destruction de la session.
 * 
 * La fonction session_destroy() termine complètement la session, supprimant toutes les informations de session sur le serveur.
 */
session_destroy();

/**
 * Suppression des cookies de connexion.
 * 
 * Les cookies 'user_id', 'username', et 'role' sont supprimés en les définissant avec une date d'expiration passée.
 */
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}
if (isset($_COOKIE['role'])) {
    setcookie('role', '', time() - 3600, '/', $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true);
}

/**
 * Redirection vers la page de connexion.
 * 
 * Après la déconnexion complète (destruction de la session et suppression des cookies),
 * l'utilisateur est redirigé vers la page de connexion (login.php).
 */
header("Location: login.php");
exit();
