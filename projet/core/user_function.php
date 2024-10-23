<?php

require_once '../config/db.php'; // Connexion à la base de données

// Fonction pour inscrire un utilisateur
function registerUser($username, $email, $password) {
    global $pdo;
    
    // Hash du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    return $stmt->execute();
}

// Fonction pour connecter un utilisateur
function loginUser($email, $password) {
    global $pdo;
    
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le mot de passe correspond
    if ($user && password_verify($password, $user['password'])) {
        // Stocker l'utilisateur dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Assigner le rôle (admin ou user)
        return true;
    }

    return false;
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est un admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

?>
