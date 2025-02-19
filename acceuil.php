<?php
class GuestDB {
    private $pdo;

    public function __construct($host, $livre, $user, $pass){
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$livre;charset=utf8", $user, $pass);
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

/* Classe livre d'or */
class GuestBook {
    private $pdo;

    public function __construct(GuestDB $database){
        $this->pdo = $database->getPdo();
    }

    // Récupérer tous les commentaires (avec pagination)
    public function getMessages($limit, $offset){
        $sql = "SELECT * FROM messages ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de commentaires
    public function getMessagesCount(){
        $sql = "SELECT COUNT(*) as count FROM messages";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Rechercher par nom ou prénom (avec pagination si $limit et $offset sont précisés)
    public function searchMessages($q, $limit = null, $offset = null){
        if ($limit !== null && $offset !== null) {
            $sql = "SELECT * FROM messages
                    WHERE nom LIKE :q OR prenom LIKE :q
                    ORDER BY id DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':q', '%'.$q.'%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM messages
                    WHERE nom LIKE :q OR prenom LIKE :q
                    ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':q' => '%'.$q.'%']);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre de commentaires pour une recherche
    public function searchMessagesCount($q){
        $sql = "SELECT COUNT(*) as count FROM messages
                WHERE nom LIKE :q OR prenom LIKE :q";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':q' => '%'.$q.'%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    // Ajouter un commentaire (nom, prénom, message)
    public function addMessage($nom, $prenom, $message){
        $sql = "INSERT INTO messages (nom, prenom, message, date_post)
                VALUES (:nom, :prenom, :message, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom'     => $nom,
            ':prenom'  => $prenom,
            ':message' => $message
        ]);
    }
}

/* INSTANCIATION + TRAITEMENT*/

// Adaptez vos identifiants MySQL
$database = new GuestDB("localhost", "livre", "root", "");
$guestBook = new GuestBook($database);

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
    $msg    = strip_tags($_POST["message"]);

    if (!empty($nom) && !empty($prenom) && !empty($msg)) {
        $guestBook->addMessage($nom, $prenom, $msg);
        $feedback = "Commentaire ajouté avec succès !";
        // Redirection pour éviter la double soumission
        header("Location: acceuil.php");
        exit;
    } else {
        $feedback = "Veuillez remplir tous les champs (nom, prénom, message).";
    }
}

// 3.2. Lecture des messages (filtrés ou non)
if ($q === "") {
    // Pas de recherche
    $totalMessages = $guestBook->getMessagesCount();
    $messages = $guestBook->getMessages($limit, $offset);
} else {
    // Recherche sur nom/prénom
    $totalMessages = $guestBook->searchMessagesCount($q);
    $messages = $guestBook->searchMessages($q, $limit, $offset);
}

$totalPages = ceil($totalMessages / $limit);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="accueil.css">
  <title>Livre d'Or</title>
</head>
<body>
  <nav>
    <a href="index.php">Bernadette's Birthday</a>
    <a href="login.php">
      <img src="image/profile.png" alt="icone profil">
    </a>
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
          <?php if (empty($messages)): ?>
            <p>Aucun commentaire trouvé.</p>
          <?php else: ?>
            <?php foreach ($messages as $msg): ?>
              <div class="message-item">
                <h3>
                  <?php echo htmlspecialchars($msg['nom']); ?>
                  <?php echo htmlspecialchars($msg['prenom']); ?>
                </h3>
                <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                <em>Posté le <?php echo date('d/m/Y H:i', strtotime($msg['date_post'])); ?></em>
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
              <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>
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
    <p></p>
    <div class="Copyright">
      <p>
        Magali Vacher<br>
        <a href="https://github.com/Vacher-Magali">
          <img src="image/githublogo.png" alt="logo github">
        </a>
      </p>
      <p>
        Anna Marras<br>
        <a href="https://github.com/anna-marras">
          <img src="image/githublogo.png" alt="logo github">
        </a>
      </p>
      <p>
        Emilie Ponce<br>
        <a href="https://github.com/emilie-ponce">
          <img src="image/githublogo.png" alt="logo github">
        </a>
      </p>
      <p>
        Jeffry KHALIFE<br>
        <a href="https://github.com/jeffry-khalife">
          <img src="image/githublogo.png" alt="logo github">
        </a>
      </p>
    </div>
  </footer>
</body>
</html>
