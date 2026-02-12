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

  public function searchByUser($userId, $q = '', $categoryId = null)
  {
    $sql = "SELECT * FROM tt_objets WHERE user_id = ?";
    $params = [(int) $userId];
    if ($q !== '') {
      $sql .= " AND (libelle LIKE ? OR description LIKE ?)";
      $like = '%' . $q . '%';
      $params[] = $like;
      $params[] = $like;
    }
    if ($categoryId) {
      $sql .= " AND category_id = ?";
      $params[] = (int) $categoryId;
    }
    $sql .= " ORDER BY id DESC";
    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Compter le total des objets
  public function countAll()
  {
    $sql = "SELECT COUNT(*) FROM tt_objets";
    $st = $this->pdo->query($sql);
    return (int)$st->fetchColumn();
  }

  // Récupérer tous les objets avec détails (catégorie, propriétaire, photo)
  public function findAllWithDetails($q = '', $categoryId = null, $ownerName = null, $page = 1, $limit = 20)
  {
    $sql = "SELECT o.*, c.libelle as category_name, u.username as owner_name, u.pdp as owner_pdp,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o.id LIMIT 1) as main_photo
            FROM tt_objets o
            LEFT JOIN tt_categories c ON o.category_id = c.id
            LEFT JOIN tt_users u ON o.user_id = u.id
            WHERE 1=1";
    $params = [];
    if ($q !== '') {
      $sql .= " AND (o.libelle LIKE ? OR o.description LIKE ?)";
      $like = '%' . $q . '%';
      $params[] = $like;
      $params[] = $like;
    }
    if ($categoryId) {
      $sql .= " AND o.category_id = ?";
      $params[] = (int)$categoryId;
    }
    if ($ownerName) {
      $sql .= " AND u.username LIKE ?";
      $params[] = '%' . $ownerName . '%';
    }
    $sql .= " ORDER BY o.id DESC";
    $offset = ($page - 1) * $limit;
    $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Compter les objets filtrés (pour pagination admin)
  public function countFiltered($q = '', $categoryId = null, $ownerName = null)
  {
    $sql = "SELECT COUNT(*) FROM tt_objets o
            LEFT JOIN tt_users u ON o.user_id = u.id
            WHERE 1=1";
    $params = [];
    if ($q !== '') {
      $sql .= " AND (o.libelle LIKE ? OR o.description LIKE ?)";
      $like = '%' . $q . '%';
      $params[] = $like;
      $params[] = $like;
    }
    if ($categoryId) {
      $sql .= " AND o.category_id = ?";
      $params[] = (int)$categoryId;
    }
    if ($ownerName) {
      $sql .= " AND u.username LIKE ?";
      $params[] = '%' . $ownerName . '%';
    }
    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return (int)$st->fetchColumn();
  }

  // Récupérer les N derniers objets avec détails
  public function findRecentWithDetails($limit = 5)
  {
    $sql = "SELECT o.*, c.libelle as category_name, u.username as owner_name, u.pdp as owner_pdp,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o.id LIMIT 1) as main_photo
            FROM tt_objets o
            LEFT JOIN tt_categories c ON o.category_id = c.id
            LEFT JOIN tt_users u ON o.user_id = u.id
            ORDER BY o.id DESC
            LIMIT " . (int)$limit;
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function searchPublic($currentUserId = null, $q = '', $categoryId = null)
  {
    $sql = "SELECT * FROM v_objets_public WHERE 1=1";
    $params = [];
    if ($currentUserId !== null) {
      $sql .= " AND user_id != ?";
      $params[] = (int) $currentUserId;
    }
    if ($q !== '') {
      $sql .= " AND (libelle LIKE ? OR description LIKE ?)";
      $like = '%' . $q . '%';
      $params[] = $like;
      $params[] = $like;
    }
    if ($categoryId) {
      $sql .= " AND category_id = ?";
      $params[] = (int) $categoryId;
    }
    $sql .= " ORDER BY id DESC";
    $st = $this->pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }
}
