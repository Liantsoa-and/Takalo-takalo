<?php
class EchangeRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

  public function findAll() {
    $sql = "SELECT e.id,
                   o1.libelle AS objet1,
                   u1.username AS user1,
                   o2.libelle AS objet2,
                   u2.username AS user2,
                   s.libelle AS status,
                   e.date_echange
            FROM tt_echanges e
            LEFT JOIN tt_objets o1 ON e.objet1_id = o1.id
            LEFT JOIN tt_users u1 ON o1.user_id = u1.id
            LEFT JOIN tt_objets o2 ON e.objet2_id = o2.id
            LEFT JOIN tt_users u2 ON o2.user_id = u2.id
            LEFT JOIN tt_status s ON e.status_id = s.id
            ORDER BY e.id DESC";
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }
  
  
}
