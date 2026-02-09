<?php
class UserRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

  public function emailExists($email) {
    $st = $this->pdo->prepare("SELECT 1 FROM users WHERE email=? LIMIT 1");
    $st->execute([(string)$email]);
    return (bool)$st->fetchColumn();
  }

  public function create($email) {
    $st = $this->pdo->prepare("
      INSERT INTO users(email)
      VALUES(?)
    ");
    $st->execute([(string)$email]);
    return $this->pdo->lastInsertId();
  }

  public function findByEmail($email) {
    $st = $this->pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $st->execute([(string)$email]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
  }
}
