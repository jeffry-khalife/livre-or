<?php

class Comment {
    private $id;
    private $comment;
    private $id_user;
    private $date;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getId() {
        return $this->id;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function getDate() {
        return $this->date;
    }

    public function save() {
        if ($this->id) {
            $stmt = $this->pdo->prepare("UPDATE comment SET comment = ?, id_user = ?, date = ? WHERE id = ?");
            $stmt->execute([$this->comment, $this->id_user, $this->date, $this->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO comment (comment, id_user, date) VALUES (?, ?, ?)");
            $stmt->execute([$this->comment, $this->id_user, $this->date]);
            $this->id = $this->pdo->lastInsertId();  
        }
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM comment WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->setId($data['id']);
            $this->setComment($data['comment']);
            $this->setIdUser($data['id_user']);
            $this->setDate($data['date']);
        }
    }

    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM comment WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
    
}