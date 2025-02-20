<?php
session_start();

// Inclure les classes nécessaires
include('connexion.php');
include('user.php');
include('comment.php');

// Vérifier si l'utilisateur est connecté et s'il est admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection vers la page de connexion si non connecté
    exit;
}

// Récupérer l'utilisateur connecté
$user = new User('localhost', 'livreor', 'root', '');
$user->getById($_SESSION['user_id']);

if (!$user->isAdmin()) {
    // Rediriger si l'utilisateur n'est pas un admin
    header('Location: index.php');
    exit;
}

// Gestion de la suppression d'un commentaire
if (isset($_GET['delete'])) {
    $comment = new Comment('localhost', 'livreor', 'root', '');
    $comment->getById($_GET['delete']);
    
    // Supprimer le commentaire si l'id est valide
    if ($comment->getId()) {
        $stmt = $comment->getPdo()->prepare("DELETE FROM comment WHERE id = ?");
        $stmt->execute([$comment->getId()]);
        header('Location: administrateur.php');
        exit;
    }
}

// Récupérer tous les commentaires
$comments = (new Comment('localhost', 'livreor', 'root', ''))->getPdo()->query("SELECT * FROM comment ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur - Livre d'Or</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<nav>
        <a href="index2.php">Bernadette's Birthday</a>
        <div class="dropdown2">
            <button class="dropbtn2"><a href="#"><img src="image/profil.png" alt="icone profil"></a></button>
            <div class="dropdown-content2">
              <a href="profil.php">Parametres</a>
              <a href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="content">
        <div class="containers">
            <h2>Gestion des commentaires</h2>

            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?= htmlspecialchars($comment['nom']) ?></td>
                            <td><?= htmlspecialchars($comment['prenom']) ?></td>
                            <td><?= nl2br(htmlspecialchars($comment['comment'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($comment['date'])) ?></td>
                            <td>
                                <!-- Bouton de suppression -->
                                <a href="administrateur.php?delete=<?= $comment['id'] ?>" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
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
