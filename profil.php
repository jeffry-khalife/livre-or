<?php
session_start(); // début de la session

// Verfier si l'utilisateur est connecté 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include ('config.php'); // page de connexion à la base de donnée 
include ('user.php'); // page de la classe user

$message = ''; 
$login = $_SESSION['login'];  

// attribution des valeurs saisis dans le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newLogin = $_POST['login'];
    $newPassword = $_POST['password'];
    
    if (empty($newLogin) || empty($newPassword)) {
        $message = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $user = new User($db); // création d'une nouvelle instance de la classe User
            $user->setId($_SESSION['user_id']); 

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $user->setLogin($newLogin);
            $user->setPassword($hashedPassword);
            $user->save();
            $_SESSION['login'] = $newLogin;
            $message = 'Informations mises à jour !';
        } catch (Exception $e) {
            $message = 'Erreur lors de la mise à jour' . $e->getMessage();
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
    <title>Profil</title>
</head>

<body>
<nav>
        <a href="index.php">Bernadette's Birthday</a>
        <div class="dropdown2">
            <button class="dropbtn2"><a href="#"><img src="image/profil.png" alt="icone profil"></a></button>
            <div class="dropdown-content2">
              <a class="active" href="profil.php">Parametres</a>
              <a href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>


    <div class="content">
        <div class="container">
        <h1>Modifier mes informations</h1>
          <form action="profil.php" method="POST">
              <label for="login">Nouveau nom d'utilisateur :</label>
              <input type="text" id="login" name="login" value="" required><br><br>

              <label for="password">Nouveau mot de passe :</label>
              <input type="password" id="password" name="password" required><br><br>

              <button type="submit">Mettre à jour</button>
          </form>
          <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
        </div>
    </div>

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

