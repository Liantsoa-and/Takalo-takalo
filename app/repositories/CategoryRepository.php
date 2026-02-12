<?php
class CategoryRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

  public function create($data) {
    $st = $this->pdo->prepare("
      INSERT INTO tt_categories(libelle)
      VALUES(?)
    ");
    $st->execute([(string)$data['libelle']]);
    return $this->pdo->lastInsertId();
  }

  public function findAll() {
    $st = $this->pdo->query("SELECT * FROM tt_categories ORDER BY libelle ASC");
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findById($id) {
    $st = $this->pdo->prepare("SELECT * FROM tt_categories WHERE id=? LIMIT 1");
    $st->execute([(int)$id]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  public function deleteById($id) {
    $st = $this->pdo->prepare("DELETE FROM tt_categories WHERE id=?");
    return $st->execute([(int)$id]);
  }

  public function update($id, $data) {
    $st = $this->pdo->prepare("
      UPDATE tt_categories
      SET libelle=?
      WHERE id=?
    ");
    return $st->execute([(string)$data['libelle'], (int)$id]);
  }

  /**
   * Compte le nombre d'objets utilisant cette catégorie
   */
  public function countObjetsByCategory($id) {
    $st = $this->pdo->prepare("SELECT COUNT(*) FROM tt_objets WHERE category_id=?");
    $st->execute([(int)$id]);
    return (int)$st->fetchColumn();
  }

  /**
   * Migre tous les objets d'une catégorie vers une autre
   */
  public function migrateObjets($fromCategoryId, $toCategoryId) {
    $st = $this->pdo->prepare("UPDATE tt_objets SET category_id=? WHERE category_id=?");
    return $st->execute([(int)$toCategoryId, (int)$fromCategoryId]);
  }
}
