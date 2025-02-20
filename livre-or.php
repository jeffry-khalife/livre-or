<?php
// livre-or.php
require_once 'Comments.php';

// Instanciation de la classe Comment qui hérite de Connexion
$guestBook = new Comment("localhost", "livreor", "root", "");

// Message de feedback pour affichage
$feedback = "";

// Récupération du paramètre de recherche
$q = isset($_GET['q']) ? trim($_GET['q']) : "";

// Définition de la pagination
$limit = 5; // Nombre de messages par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// Traitement de l'ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ajouter"])) {
    $nom     = strip_tags($_POST["nom"]);
    $prenom  = strip_tags($_POST["prenom"]);
    $comments = strip_tags($_POST["comment"]);

    if (!empty($nom) && !empty($prenom) && !empty($comments)) {
        $guestBook->addMessage($nom, $prenom, $comments);
        $feedback = "Commentaire ajouté avec succès !";
        // Redirection pour éviter la double soumission
        header("Location: livre-or.php");
        exit;
    } else {
        $feedback = "Veuillez remplir tous les champs (nom, prénom, commentaire).";
    }
}

// Lecture des messages (avec ou sans recherche)
if ($q === "") {
    $commentaires = $guestBook->getMessages($limit, $offset);
} else {
    $commentaires = $guestBook->searchMessages($q, $limit, $offset);
}

// Récupération du nombre total de messages pour la pagination
$totalMessages = $guestBook->getTotalMessages();
$totalPages = ceil($totalMessages / $limit);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style2.css">
  <link rel="stylesheet" href="livre-or.css">
  <title>Livre d'Or</title>
</head>
<body>
<nav>
    <a href="index2.php">Bernadette's Birthday</a>
    <div class="dropdown2">
        <button class="dropbtn2">
          <a href="#"><img src="image/profil.png" alt="icone profil"></a>
        </button>
        <div class="dropdown-content2">
          <a href="profil.php">Paramètres</a>
          <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</nav>
  
<main>
    <h1>Livre d'Or</h1>
    <?php if (!empty($feedback)): ?>
      <p id="feedback"><?php echo $feedback; ?></p>
    <?php endif; ?>

    <div class="flip-container">
      <input type="checkbox" id="flip-toggle">
      <div class="flipper">
        <!-- FACE AVANT : Affichage et Recherche -->
        <div class="page front">
          <h2>Commentaires</h2>
          <form class="search-form" method="get">
            <input type="text" name="q" placeholder="Rechercher par nom ou prénom..."
                   value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit">Rechercher</button>
          </form>

          <?php if (empty($commentaires)): ?>
            <p>Aucun commentaire trouvé.</p>
          <?php else: ?>
            <?php foreach ($commentaires as $comm): ?>
              <div class="message-item">
                <h3>
                  <?php echo htmlspecialchars($comm['nom']); ?>
                  <?php echo htmlspecialchars($comm['prenom']); ?>
                </h3>
                <p><?php echo nl2br(htmlspecialchars($comm['comment'])); ?></p>
                <em>Posté le <?php echo date('d/m/Y H:i', strtotime($comm['date'])); ?></em>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if ($totalPages > 1): ?>
            <div class="pagination">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                  <span class="current-page"><?= $i ?></span>
                <?php else: ?>
                  <a href="?page=<?= $i ?><?php if (!empty($q)) echo '&q=' . urlencode($q); ?>"><?= $i ?></a>
                <?php endif; ?>
              <?php endfor; ?>
            </div>
          <?php endif; ?>

          <label for="flip-toggle" class="toggle-btn">Ajouter un commentaire</label>
        </div>

        <!-- FACE ARRIÈRE : Formulaire d'ajout -->
        <div class="page back">
          <h2>Ajouter un commentaire</h2>
          <form method="post">
            <div class="form-champ">
              <input type="text" name="nom" placeholder="Votre nom..." required>
            </div>
            <div class="form-champ">
              <input type="text" name="prenom" placeholder="Votre prénom..." required>
            </div>
            <div class="form-champ">
              <textarea name="comment" rows="5" placeholder="Votre commentaire..." required></textarea>
            </div>
            <button type="submit" name="ajouter" class="envoyer-btn">Envoyer</button>
          </form>
          <label for="flip-toggle" class="toggle-btn">Voir les commentaires</label>
        </div>
      </div>
    </div>
</main>

<footer>
    © Copyright
    <div class="Copyright">
        <p>Magali Vacher
        <br><a href="https://github.com/Vacher-Magali"><img src="image/githublogo.png" alt="logo github"></a></p>
        <p>Anna Marras
        <br><a href="https://github.com/Anna-Marras"><img src="image/githublogo.png" alt="logo github"></a></p>
        <p>Emilie Ponce
        <br><a href="https://github.com/emilie-ponce"><img src="image/githublogo.png" alt="logo github"></a></p>
        <p>Jeffry Khalife
        <br><a href="https://github.com/jeffry-khalife"><img src="image/githublogo.png" alt="logo github"></a></p>
    </div>
</footer>
</body>
</html>

