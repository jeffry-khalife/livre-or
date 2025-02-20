<?php
include ('connexion.php'); // Inclure la classe Connexion
include ('user.php'); // Inclure la classe User

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        $message = 'Tous les champs sont obligatoires';
    } else {
        try {
            // Créer une instance de Connexion pour obtenir l'objet PDO
            $connexion = new Connexion('localhost', 'livreor', 'root', ''); // Paramètres de la base de données

            // Créer une nouvelle instance de User en lui passant l'objet PDO
            $user = new User('localhost', 'livreor', 'root', '');

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setLogin($login);
            $user->setPassword($hashedPassword);
            $user->save(); 

            $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
        } catch (Exception $e) {
            $message = 'Erreur lors de l\'inscription : ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Caveat' rel='stylesheet'>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="style2.css">
    <title>Inscription</title>
</head>

<body>
<nav>
        <a href="index.php">Bernadette's Birthday</a>
        <a href="login.php"><img src="image/profil.png" alt="icone profil"></a>
</nav>

    <div class="content">
        <div class="container">
            <h1>Inscription</h1>
            <form action="signup.php" method="POST">
                <label for="login">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" required><br><br>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br><br>
                
                <button type="submit">S'inscrire</button> 
            </form>
            <?php if ($message): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <p>Déjà un compte ? <a href="login.php">Connexion</a></p>
        </div>
    </div>
</body>
</html>


    <footer>
        © Copyright
        <div class="Copyright">
            <p>Magali Vacher
            <br><a href="https://github.com/Vacher-Magali"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Anna Marras
            <br><a href="https://github.com/Anna-Marras"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Emilie Ponce
            <br><a href="https://github.com/emilie-ponce"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Jeffry Khalife
            <br><a href="https://github.com/jeffry-khalife"><img src = "image/githublogo.png" alt="logo github"></a></p>
        </div>
    </footer>
</body>
</html>