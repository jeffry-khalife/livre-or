<?php
session_start();
include ('config.php');
include ('user.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        $message = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login'] = $user['login'];

                header('Location: livre-or.php'); 
                exit;
            } else {
                $message = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
        } catch (Exception $e) {
            $message = 'Erreur de connexion : ' . $e->getMessage();
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
        <a href="login.php"><img src="image/profil.png" alt="icone profil"></a>
    </nav>


    <div class="content">
        <div class="container">
        <h1>Connexion</h1>
    <form action="login.php" method="POST">
        <label for="login">Nom d'utilisateur :</label>
        <input type="text" id="login" name="login" required><br><br>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Se connecter</button>
    </form>
            <p>Pas de compte ? <a href="signup.php">Inscription</a></p>
        </div>
    </div>

    <footer>
        © Copyright
        <div class="Copyright">
            <p>Magali Vacher
            <br><a href="#"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Anna Marras
            <br><a href="#"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Emilie Ponce
            <br><a href="#"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Jeffry KHALIFE
            <br><a href="https://github.com/jeffry-khalife"><img src = "image/githublogo.png" alt="logo github"></a></p>
        </div>
    </footer>
</body>
</html>