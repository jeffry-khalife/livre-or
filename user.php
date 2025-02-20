<?php

class User {
    private $id;
    private $login;
    private $password;
    private $role; 
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function verifyPassword($currentPassword) {
        $stmt = $this->pdo->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->execute([$this->id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return password_verify($currentPassword, $user['password']);
        }
        return false;
    }

    public function save() {
        if ($this->id) {
            $stmt = $this->pdo->prepare("UPDATE user SET login = ?, password = ?, role = ? WHERE id = ?");
            $stmt->execute([$this->login, $this->password, $this->role, $this->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO user (login, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$this->login, $this->password, $this->role]);
            $this->id = $this->pdo->lastInsertId();
        }
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->setId($data['id']);
            $this->setLogin($data['login']);
            $this->setPassword($data['password']);
            $this->setRole($data['role']); 
        }
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isUser() {
        return $this->role === 'user';
    }
}

?>