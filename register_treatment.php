<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion à la base de données
        $mysqlClient = new PDO('mysql:host=localhost;dbname=crud eleves;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Validation des champs du formulaire
    if (!isset($_POST['Nom']) || empty($_POST['Nom']) ||
        !isset($_POST['email']) || empty($_POST['email']) ||
        !isset($_POST['Mot_de_Passe']) || empty($_POST['Mot_de_Passe']) ||
        !isset($_POST['confirmation_password']) || empty($_POST['confirmation_password'])
    ) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs du formulaire.';
        header('Location: Register.php');
        exit;
    }

    // Récupération des données du formulaire
    $Nom = htmlspecialchars($_POST['Nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $Mot_de_Passe = $_POST['Mot_de_Passe'];
    $confirmation_password = $_POST['confirmation_password'];

    // Vérifier si les mots de passe correspondent
    if ($Mot_de_Passe !== $confirmation_password) {
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
        $_SESSION['form_data'] = $_POST; // Sauvegarder les données
        header('Location: Register.php');
        exit;
    }

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Adresse email invalide.';
        $_SESSION['form_data'] = $_POST; // Sauvegarder les données
        header('Location: Register.php');
        exit;
    }

    // Vérifier si l'email a déjà été utilisé
    try {
        $checkEmail = $mysqlClient->prepare('SELECT COUNT(*) FROM registration WHERE email = :email');
        $checkEmail->execute(['email' => $email]);
        $emailExists = $checkEmail->fetchColumn();

        if ($emailExists > 0) {
            $_SESSION['error'] = 'Cet email a déjà été utilisé. Veuillez vous connecter.';
            $_SESSION['form_data'] = $_POST; // Sauvegarder les données
            header('Location: Register.php');
            exit;
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($Mot_de_Passe, PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $insertmessage = $mysqlClient->prepare('INSERT INTO registration (Nom, email, Mot_de_Passe, confirmation_password) VALUES (:Nom, :email, :Mot_de_Passe, :confirmation_password)');
        $insertmessage->execute([
            'Nom' => $Nom,
            'email' => $email,
            'Mot_de_Passe' => $hashedPassword,
            'confirmation_password' => $confirmation_password,
        ]);

        // Inscription réussie, rediriger vers la page de connexion avec message de succès
        $_SESSION['success'] = 'Votre compte a été créé avec succès. Veuillez vous connecter.';
        header('Location: login.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'insertion : " . $e->getMessage();
        header('Location: register.php');
        exit;
    }
} else {
    // Si la requête n'est pas POST, rediriger vers le formulaire
    header('Location: register.php');
    exit;
}
