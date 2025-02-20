<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('connexion.php');
    require_once('user.php');

    $login = $_POST['login'];
    $password = $_POST['password'];

    try {
        $database = new connexion("localhost", "livreor", "root", "");
        $db = $database->getPDO();

        $user = new User('localhost', 'livreor', 'root', '');
        $stmt = $db->prepare("SELECT * FROM user WHERE login = ?");
        $stmt->execute([$login]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && password_verify($password, $data['password'])) {

            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role'] = $data['role'];

            if ($data['role'] == 'admin') {
                header('Location: administrateur.php');
            } else {
                header('Location: livre-or.php');
            }
            exit();
        } else {
            $error = 'Identifiants incorrects.';
        }
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
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
            <br><a href="https://github.com/Vacher-Magali"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Anna Marras
            <br><a href="https://github.com/Anna-Marras"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Emilie Ponce
            <br><a href="https://github.com/emilie-ponce"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Jeffry Kalife
            <br><a href="https://github.com/jeffry-khalife"><img src = "image/githublogo.png" alt="logo github"></a></p>
        </div>
    </footer>
</body>
</html>