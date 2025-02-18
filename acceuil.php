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

    // Récupérer tous les commentaires 
    public function getAllMessages(){
        $sql = "SELECT * FROM messages ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Rechercher par nom ou prénom
    public function searchMessages($q){
        $sql = "SELECT * FROM messages
                WHERE nom LIKE :q OR prenom LIKE :q
                ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':q' => '%'.$q.'%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

// 3.1. Ajout d'un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["ajouter"])) {
    $nom    = strip_tags($_POST["nom"]);
    $prenom = strip_tags($_POST["prenom"]);
    $msg    = strip_tags($_POST["message"]);

    if (!empty($nom) && !empty($prenom) && !empty($msg)) {
        $guestBook->addMessage($nom, $prenom, $msg);
        $feedback = "Commentaire ajouté avec succès !";
        // Redirection pour éviter la double soumission
        header("Location: livreor.php");
        exit;
    } else {
        $feedback = "Veuillez remplir tous les champs (nom, prénom, message).";
    }
}

// 3.2. Lecture des messages (filtrés ou non)
if ($q === "") {
    // Pas de recherche
    $messages = $guestBook->getAllMessages();
} else {
    // Recherche sur nom/prénom
    $messages = $guestBook->searchMessages($q);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="acceuil.css">
  <title>Livre d'Or</title>

</head>
<nav>
        <a href="index.php">Bernadette's Birthday</a>
        <a href="login.php"><img src="image/profile.png" alt="icone profil"></a>
    </nav>
<body>

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
          <?php foreach($messages as $msg): ?>
            <div class="message-item">
              <h3><?php echo htmlspecialchars($msg['nom']); ?> 
                  <?php echo htmlspecialchars($msg['prenom']); ?></h3>
              <p><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
              <em>Posté le <?php echo date('d/m/Y H:i', strtotime($msg['date_post'])); ?></em>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <!-- Bouton pour flip vers la face arrière -->
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

        <!-- Bouton pour flip retour face avant -->
        <label for="flip-toggle" class="toggle-btn">Voir les commentaires</label>
      </div>
      <footer>
        © Copyright
        <div class="Copyright">
            <p>Magali Vacher
            <br><a href="https://github.com/Vacher-Magali"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Anna Maras
            <br><a href="#"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Emilie Ponce
            <br><a href="#"><img src = "image/githublogo.png" alt="logo github"></a></p>
            <p>Jeffry KHALIFE
            <br><a href="https://github.com/jeffry-khalife"><img src = "image/githublogo.png" alt="logo github"></a></p>
        </div>
    </footer>
</body>
</html>