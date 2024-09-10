<?php
session_start();

// Connexion à la base de données
try {
    $mysqlClient = new PDO('mysql:host=localhost;dbname=crud eleves;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    if (!isset($_POST['email']) || empty($_POST['email']) ||
        !isset($_POST['Mot_de_Passe']) || empty($_POST['Mot_de_Passe'])) {
        die('Veuillez remplir tous les champs.');
    }

    $email = htmlspecialchars($_POST['email']);
    $Mot_de_passe = $_POST['Mot_de_Passe'];

    // Rechercher l'utilisateur dans la base de données
    $query = $mysqlClient->prepare('SELECT * FROM registration WHERE email = :email');
    $query->execute(['email' => $email]);
    $user = $query->fetch();

    if ($user && password_verify($Mot_de_Passe, $user['Mot_de_Passe'])) {
        // Connexion réussie
        $_SESSION['id'] = $user['id'];
        header('Location: dashboard.php');  // Rediriger vers une page de tableau de bord après connexion
        exit;
    } else {
        echo "Identifiants incorrects.";
    }
}
