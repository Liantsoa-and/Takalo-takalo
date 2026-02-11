<?php
class ObjetRepository
{
  private $pdo;
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function findByUserId($userId)
  {
    $st = $this->pdo->prepare("SELECT * FROM tt_objets WHERE user_id=?");
    $st->execute([(int) $userId]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $st = $this->pdo->prepare("
      INSERT INTO tt_objets(libelle, description, category_id, user_id, prix_estimatif)
      VALUES(?, ?, ?, ?, ?)
    ");
    $st->execute([(string) $data['libelle'], (string) $data['description'], (int) $data['category_id'], (int) $data['user_id'], (float) $data['prix_estimatif']]);
    return $this->pdo->lastInsertId();
  }

  public function findAll()
  {
    $st = $this->pdo->query("SELECT * FROM tt_objets");
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findById($id)
  {
    $st = $this->pdo->prepare("SELECT * FROM tt_objets WHERE id=? LIMIT 1");
    $st->execute([(int) $id]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function deleteById($id)
  {
    $st = $this->pdo->prepare("DELETE FROM tt_objets WHERE id=?");
    $st->execute([(int) $id]);
  }

  public function update($id, $data)
  {
    $st = $this->pdo->prepare("
      UPDATE tt_objets
      SET libelle=?, description=?, category_id=?, prix_estimatif=?
      WHERE id=?
    ");
    $st->execute([(string) $data['libelle'], (string) $data['description'], (int) $data['category_id'], (float) $data['prix_estimatif'], (int) $id]);
  }

  public function getObjetsByOthers($currentUserId = null, $categoryId = null, $page = 1, $limit = 10)
  {
    $offset = ($page - 1) * $limit;
    $sql = "SELECT * FROM v_objets_public WHERE 1=1";
    $params = [];

    if ($currentUserId !== null) {
      $sql .= " AND user_id != ?";
      $params[] = (int) $currentUserId;
    }

    if ($categoryId !== null) {
      $sql .= " AND category_id = ?";
      $params[] = (int) $categoryId;
    }

    $sql .= " ORDER BY id DESC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function countObjetsByOthers($currentUserId = null, $categoryId = null)
  {
    $sql = "SELECT COUNT(*) FROM v_objets_public WHERE 1=1";
    $params = [];

    if ($currentUserId !== null) {
      $sql .= " AND user_id != ?";
      $params[] = (int) $currentUserId;
    }

    if ($categoryId !== null) {
      $sql .= " AND category_id = ?";
      $params[] = (int) $categoryId;
    }

    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return (int) $st->fetchColumn();
  }

  public function getObjetPublicById($id)
  {
    $sql = "SELECT * FROM v_objets_public WHERE id = ?";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $id]);
    return $st->fetch(PDO::FETCH_ASSOC);
  }
}
