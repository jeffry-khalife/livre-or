<?php
class connexion {
    private $pdo;

    public function __construct($host, $livreor, $user, $pass){
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$livreor;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getPdo(){
        return $this->pdo;
    }
}

/* Classecomment */
class comment {
    private $pdo;

    public function __construct(connexion $database){
        $this->pdo = $database->getPdo();
    }

    // Récupérer tous les commentaires 
    public function getAllMessages(){
        $sql = "SELECT * FROM comment ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les commentaires (avec pagination)
    public function getMessages($limit, $offset){
        $sql = "SELECT * FROM comment ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Rechercher par nom ou prénom
    public function searchMessages($q, $limit = null, $offset = null){
        if ($limit !== null && $offset !== null) {
            $sql = "SELECT * FROM comment
                    WHERE nom LIKE :q OR prenom LIKE :q
                    ORDER BY id DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':q', '%'.$q.'%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM comment
                    WHERE nom LIKE :q OR prenom LIKE :q
                    ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':q' => '%'.$q.'%']);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un commentaire (nom, prénom, message)
    public function addMessage($nom, $prenom, $comment){
        $sql = "INSERT INTO comment (nom, prenom, comment, date)
                VALUES (:nom, :prenom, :comment, CURRENT_TIMESTAMP())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'     => $nom,
            ':prenom'  => $prenom,
            ':comment' => $comment
        ]);
    }

    // Récupérer le nombre total de commentaires (pour la pagination)
    public function getTotalMessages(){
        $sql = "SELECT COUNT(*) FROM comment";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
}

/* INSTANCIATION + TRAITEMENT*/

// Adaptez vos identifiants MySQL
$database = new connexion("localhost", "livreor", "root", "");
$guestBook = new comment($database);

// Pour afficher un message de succès ou d'erreur
$feedback = "";

// Récupération du paramètre de recherche
$q = isset($_GET['q']) ? trim($_GET['q']) : "";

// Définition de la pagination
$limit = 5; // Nombre de messages par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$offset = ($page - 1) * $limit;

// 3.1. Ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ajouter"])) {
    $nom    = strip_tags($_POST["nom"]);
    $prenom = strip_tags($_POST["prenom"]);
    $comments    = strip_tags($_POST["comment"]);

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

// 3.2. Lecture des messages (filtrés ou non)
if ($q === "") {
    // Pas de recherche, avec pagination
    $comment = $guestBook->getMessages($limit, $offset);
} else {
    // Recherche sur nom/prénom, avec pagination
    $comment = $guestBook->searchMessages($q, $limit, $offset);
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
            <button class="dropbtn2"><a href="#"><img src="image/profil.png" alt="icone profil"></a></button>
            <div class="dropdown-content2">
              <a href="profil.php">Parametres</a>
              <a href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
  
  <main>
    <h1>Livre d'Or</h1>

    <!-- Affichage du message de feedback -->
    <?php if (!empty($feedback)): ?>
      <p id="feedback"><?php echo $feedback; ?></p>
    <?php endif; ?>

    <div class="flip-container">
      <input type="checkbox" id="flip-toggle">
      <div class="flipper">
        <!-- FACE AVANT : Affichage + Recherche -->
        <div class="page front">
          <h2>Commentaires</h2>

          <!-- Barre de recherche -->
          <form class="search-form" method="get">
            <input type="text" name="q" placeholder="Rechercher par nom ou prénom..."
                   value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit">Rechercher</button>
          </form>

          <!-- Liste des messages -->
          <?php if (empty($comment)): ?>
            <p>Aucun commentaire trouvé.</p>
          <?php else: ?>
            <?php foreach ($comment as $comments): ?>
              <div class="message-item">
                <h3>
                  <?php echo htmlspecialchars($comments['nom']); ?>
                  <?php echo htmlspecialchars($comments['prenom']); ?>
                </h3>
                <p><?php echo nl2br(htmlspecialchars($comments['comment'])); ?></p>
                <em>Posté le <?php echo date('d/m/Y H:i', strtotime($comments['date'])); ?></em>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Liens de pagination -->
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

          <!-- Bouton pour passer à l'ajout -->
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

          <!-- Bouton pour revenir à l'affichage -->
          <label for="flip-toggle" class="toggle-btn">Voir les commentaires</label>
        </div>
      </div>
    </div>
  </main>
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