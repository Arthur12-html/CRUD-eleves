<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h1>Créer ton compte</h1>
            
            <!-- Affichage des messages d'erreur ou de succès -->
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <form action="register_treatment.php" method="POST">
                <input type="text" name="Nom" placeholder="Entrez votre Nom" value="<?= $_SESSION['form_data']['Nom'] ?? ''; ?>" required>
                <input type="email" name="email" placeholder="Entrez votre email" value="<?= $_SESSION['form_data']['email'] ?? ''; ?>" required>
                <input type="password" name="Mot_de_Passe" placeholder="Mot de Passe" required>
                <input type="password" name="confirmation_password" placeholder="Confirmez votre mot de passe" required>

                <button type="submit" class="sign-in-btn">S'inscrire</button>
            </form><br><br>
            <p class="connection">Déjà un compte ? <a href="login.php">Se Connecter</a></p>
        </div>
        <div class="info-section">
            <h1>Bienvenue sur notre plateforme!</h1>
            <h2>Rejoignez des milliers d'élèves</h2>
            <p>La seule façon de faire du bon travail est d'aimer ce que vous faites. Si vous n'avez pas encore trouvé, continuez à chercher.</p>
            <div class="sales-report">
                <img src="Images/Working students.png" alt="working students">
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Supprimer les données de session après affichage
unset($_SESSION['form_data']);
?>
