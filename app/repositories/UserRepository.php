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

  public function findByUsername($username) {
    $st = $this->pdo->prepare("SELECT * FROM tt_users WHERE username=? LIMIT 1");
    $st->execute([(string)$username]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function findAll() {
    $st = $this->pdo->query("SELECT * FROM tt_users");
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findAllUtilisateur() {
    $st = $this->pdo->query("SELECT * FROM tt_users WHERE role='user'");
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findById($id) {
    $st = $this->pdo->prepare("SELECT * FROM tt_users WHERE id = ? LIMIT 1");
    $st->execute([(int)$id]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function createFull(array $data) {
    $st = $this->pdo->prepare("INSERT INTO tt_users (username, password, role, pdp) VALUES (?, ?, ?, ?)");
    $st->execute([
      isset($data['username']) ? $data['username'] : null,
      isset($data['password']) && $data['password'] !== '' ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
      isset($data['role']) ? $data['role'] : 'user',
      isset($data['pdp']) ? $data['pdp'] : 'default.png'
    ]);
    return $this->pdo->lastInsertId();
  }

  public function update($id, array $data) {
    $fields = [];
    $params = [];
    if (isset($data['username'])) { $fields[] = 'username = ?'; $params[] = $data['username']; }
    if (isset($data['password']) && $data['password'] !== '') { $fields[] = 'password = ?'; $params[] = password_hash($data['password'], PASSWORD_DEFAULT); }
    if (isset($data['role'])) { $fields[] = 'role = ?'; $params[] = $data['role']; }
    if (isset($data['pdp'])) { $fields[] = 'pdp = ?'; $params[] = $data['pdp']; }
  if (isset($data['bio'])) { $fields[] = 'bio = ?'; $params[] = $data['bio']; }
    if (empty($fields)) { return false; }
    $params[] = (int)$id;
    $sql = "UPDATE tt_users SET " . implode(', ', $fields) . " WHERE id = ?";
    $st = $this->pdo->prepare($sql);
    return $st->execute($params);
  }

  public function delete($id) {
    $st = $this->pdo->prepare("DELETE FROM tt_users WHERE id = ?");
    return $st->execute([(int)$id]);
  }

  // Compter tous les utilisateurs
  public function countAll() {
    $st = $this->pdo->query("SELECT COUNT(*) FROM tt_users");
    return (int)$st->fetchColumn();
  }

  // Utilisateurs avec le plus d'objets
  public function topUsersByObjets($limit = 5) {
    $sql = "SELECT u.id, u.username, u.role, u.pdp, COUNT(o.id) as nb_objets
            FROM tt_users u
            LEFT JOIN tt_objets o ON o.user_id = u.id
            GROUP BY u.id, u.username, u.role, u.pdp
            HAVING nb_objets > 0
            ORDER BY nb_objets DESC
            LIMIT " . (int)$limit;
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }
}
