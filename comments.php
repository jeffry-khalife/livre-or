<?php
// Comment.php
require_once 'Connexion.php';

class Comment extends Connexion {

    // Récupérer tous les commentaires
    public function getAllMessages(){
        $sql = "SELECT * FROM comment ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les commentaires avec pagination
    public function getMessages($limit, $offset){
        $sql = "SELECT * FROM comment ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Rechercher des commentaires par nom ou prénom, avec ou sans pagination
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
?>

