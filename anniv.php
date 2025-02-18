<?php
  // fichier : livreor.php

  $filename = 'messages.txt';

  // Traitement du formulaire d'ajout d'un message
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
  // On retire les balises HTML pour une première sécurité
  $message = strip_tags($_POST['message']);
  // On ajoute le message au fichier (un message par ligne)
  file_put_contents($filename, $message . "\n", FILE_APPEND);
  // Redirection pour éviter la réinsertion du message en cas de rafraîchissement
  header("Location: livreor.php");
  exit;
  }

  // Récupération des messages enregistrés
  $messages = [];
  if (file_exists($filename)) {
  $messages = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livre d'Or Anniversaire</title>
  <link rel="stylesheet" href="aniv.css">
</head>
<body>
  <div class="flip-container">
    <!-- La case à cocher cachée pour activer l'animation -->
    <input type="checkbox" id="flip-toggle">
    <div class="flipper">
      <!-- Face avant : affichage des messages -->
      <div class="page front">
        <h2>Livre d'Or</h2>
        <?php if (empty($messages)): ?>
          <p>Aucun message pour le moment.</p>
        <?php else: ?>
          <?php foreach($messages as $msg): ?>
            <p><?php echo htmlspecialchars($msg); ?></p>
          <?php endforeach; ?>
        <?php endif; ?>
        <!-- Bouton pour accéder à la page d'ajout (active le checkbox) -->
        <label for="flip-toggle" class="toggle-btn">Ajouter un message</label>
      </div>
      <!-- Face arrière : formulaire pour ajouter un message -->
      <div class="page back">
        <h2>Ajouter un message</h2>
        <form action="livreor.php" method="post">
          <textarea name="message" rows="5" placeholder="Votre message..."></textarea><br>
          <input type="submit" value="Envoyer" class="toggle-btn">
        </form>
        <!-- Bouton pour revenir à l'affichage des messages -->
        <label for="flip-toggle" class="toggle-btn">Voir les messages</label>
      </div>
    </div>
  </div>
</body>
</html>