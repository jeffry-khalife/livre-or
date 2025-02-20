<?php

class Connexion {
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

?>
