<?php
class EchangeRepository
{
  private $pdo;
  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function findAll()
  {
    $sql = "SELECT e.id,
                   o1.libelle AS objet1,
                   u1.username AS user1,
                   o2.libelle AS objet2,
                   u2.username AS user2,
                   s.libelle AS status,
                   e.date_echange
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_users u1 ON e.user1_id = u1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_users u2 ON e.user2_id = u2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            ORDER BY e.id DESC";
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Créer une nouvelle proposition d'échange
  public function createEchange($objet1_id, $objet2_id, $user1_id, $user2_id)
  {
    // Statut par défaut : "en attente" (id = 1)
    $sql = "INSERT INTO tt_echanges (objet1_id, objet2_id, user1_id, user2_id, status_id) VALUES (?, ?, ?, ?, 1)";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $objet1_id, (int) $objet2_id, (int) $user1_id, (int) $user2_id]);
    return $this->pdo->lastInsertId();
  }

  // Vérifier si un échange existe déjà entre deux objets
  public function echangeExists($objet1_id, $objet2_id)
  {
    $sql = "SELECT COUNT(*) as count FROM tt_echanges 
            WHERE (objet1_id = ? AND objet2_id = ?) 
               OR (objet1_id = ? AND objet2_id = ?)";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $objet1_id, (int) $objet2_id, (int) $objet2_id, (int) $objet1_id]);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
  }

  // Récupérer les échanges ACCEPTÉS pour un objet (historique de propriété)
  public function getEchangesByObjetId($objet_id)
  {
    $sql = "SELECT e.id,
                   e.status_id,
                   e.user1_id, e.user2_id,
                   o1.id as objet1_id, o1.libelle AS objet1_libelle,
                   u1.username AS user1_name, u1.pdp AS user1_pdp,
                   (SELECT p1.url FROM tt_photos_objet p1 WHERE p1.objet_id = o1.id LIMIT 1) AS objet1_photo,
                   o2.id as objet2_id, o2.libelle AS objet2_libelle,
                   u2.username AS user2_name, u2.pdp AS user2_pdp,
                   (SELECT p2.url FROM tt_photos_objet p2 WHERE p2.objet_id = o2.id LIMIT 1) AS objet2_photo,
                   s.libelle AS status,
                   e.date_echange
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_users u1 ON e.user1_id = u1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_users u2 ON e.user2_id = u2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            WHERE (e.objet1_id = ? OR e.objet2_id = ?)
              AND e.status_id = 2
            ORDER BY e.date_echange ASC";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $objet_id, (int) $objet_id]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Récupérer les propositions REÇUES par un utilisateur (où ses objets sont ciblés)
  public function getPropositionsRecues($userId)
  {
    $sql = "SELECT e.id,
                   e.status_id,
                   o1.id as objet_propose_id, 
                   o1.libelle AS objet_propose,
                   o1.prix_estimatif as prix_propose,
                   e.user1_id as proposant_id,
                   u1.username AS proposant,
                   o2.id as mon_objet_id, 
                   o2.libelle AS mon_objet,
                   o2.prix_estimatif as mon_prix,
                   s.libelle AS status,
                   e.date_echange,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o1.id LIMIT 1) as photo_propose,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) as ma_photo
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_users u1 ON e.user1_id = u1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            WHERE e.user2_id = ?
            ORDER BY e.date_echange DESC";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $userId]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Récupérer les propositions ENVOYÉES par un utilisateur (où il propose ses objets)
  public function getPropositionsEnvoyees($userId)
  {
    $sql = "SELECT e.id,
                   e.status_id,
                   o1.id as mon_objet_id, 
                   o1.libelle AS mon_objet,
                   o1.prix_estimatif as mon_prix,
                   o2.id as objet_cible_id, 
                   o2.libelle AS objet_cible,
                   o2.prix_estimatif as prix_cible,
                   e.user2_id as destinataire_id,
                   u2.username AS destinataire,
                   s.libelle AS status,
                   e.date_echange,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o1.id LIMIT 1) as ma_photo,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) as photo_cible
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_users u2 ON e.user2_id = u2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            WHERE e.user1_id = ?
            ORDER BY e.date_echange DESC";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $userId]);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Accepter un échange (changer le statut à "confirmé" et échanger les propriétaires)
  public function accepterEchange($echangeId)
  {
    try {
      $this->pdo->beginTransaction();

      // Récupérer les infos de l'échange
      $sql = "SELECT objet1_id, objet2_id FROM tt_echanges WHERE id = ?";
      $st = $this->pdo->prepare($sql);
      $st->execute([(int) $echangeId]);
      $echange = $st->fetch(PDO::FETCH_ASSOC);

      if (!$echange) {
        throw new Exception("Échange non trouvé");
      }

      // Récupérer les propriétaires des objets
      $sql = "SELECT user_id FROM tt_objets WHERE id = ?";
      $st = $this->pdo->prepare($sql);

      $st->execute([(int) $echange['objet1_id']]);
      $user1 = $st->fetch(PDO::FETCH_ASSOC)['user_id'];

      $st->execute([(int) $echange['objet2_id']]);
      $user2 = $st->fetch(PDO::FETCH_ASSOC)['user_id'];

      // Échanger les propriétaires
      $sql = "UPDATE tt_objets SET user_id = ? WHERE id = ?";
      $st = $this->pdo->prepare($sql);
      $st->execute([$user2, $echange['objet1_id']]); // objet1 va à user2
      $st->execute([$user1, $echange['objet2_id']]); // objet2 va à user1

      // Mettre à jour le statut à "confirmé" (id = 2)
      $sql = "UPDATE tt_echanges SET status_id = 2 WHERE id = ?";
      $st = $this->pdo->prepare($sql);
      $st->execute([(int) $echangeId]);

      $this->pdo->commit();
      return true;
    } catch (Exception $e) {
      $this->pdo->rollBack();
      return false;
    }
  }

  // Refuser un échange (changer le statut à "refusé")
  public function refuserEchange($echangeId)
  {
    // Mettre à jour le statut à "refusé" (id = 3)
    $sql = "UPDATE tt_echanges SET status_id = 3 WHERE id = ?";
    $st = $this->pdo->prepare($sql);
    return $st->execute([(int) $echangeId]);
  }

  // Compter les échanges par statut
  public function countByStatus($statusId)
  {
    $sql = "SELECT COUNT(*) FROM tt_echanges WHERE status_id = ?";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int)$statusId]);
    return (int)$st->fetchColumn();
  }

  // Compter tous les échanges
  public function countAll()
  {
    $sql = "SELECT COUNT(*) FROM tt_echanges";
    $st = $this->pdo->query($sql);
    return (int)$st->fetchColumn();
  }

  // Récupérer les N derniers échanges
  public function findRecent($limit = 5)
  {
    $sql = "SELECT e.id,
                   o1.libelle AS objet1,
                   u1.username AS user1,
                   o2.libelle AS objet2,
                   u2.username AS user2,
                   s.libelle AS status,
                   e.date_echange
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_users u1 ON e.user1_id = u1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_users u2 ON e.user2_id = u2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            ORDER BY e.id DESC
            LIMIT " . (int)$limit;
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Récupérer un échange par ID
  public function findById($id)
  {
    $sql = "SELECT e.*
            FROM tt_echanges e
            WHERE e.id = ?";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $id]);
    return $st->fetch(PDO::FETCH_ASSOC);
  }

  // Récupérer l'historique d'appartenance d'un objet (liste des propriétaires dans le temps)
  public function getOwnershipHistory($objetId)
  {
    // Récupérer l'état courant (propriétaire actuel)
    $st = $this->pdo->prepare("SELECT o.user_id, u.username, u.pdp 
                               FROM tt_objets o 
                               INNER JOIN tt_users u ON o.user_id = u.id 
                               WHERE o.id = ? LIMIT 1");
    $st->execute([(int)$objetId]);
    $current = $st->fetch(PDO::FETCH_ASSOC);
    
    if (!$current) {
      return [];
    }
    
    $currentOwnerId = (int)$current['user_id'];

    // Utiliser la vue v_echange_comp pour récupérer les échanges confirmés
    $sql = "SELECT * FROM v_echange_comp 
            WHERE (objet1_id = ? OR objet2_id = ?) AND status_id = 2
            ORDER BY date_echange DESC";

    $st = $this->pdo->prepare($sql);
    $st->execute([(int)$objetId, (int)$objetId]);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    $timeline = [];
    
    // Ajouter le propriétaire actuel
    $timeline[] = [
      'user_id' => $current['user_id'],
      'username' => $current['username'],
      'pdp' => $current['pdp'] ?? 'default.png',
      'date' => null,
      'note' => 'Propriétaire actuel'
    ];

    $trackingOwner = $currentOwnerId;

    // Parcourir les échanges du plus récent au plus ancien
    foreach ($rows as $r) {
      if ((int)$r['objet1_id'] === (int)$objetId) {
        // L'objet était objet1 : après échange il appartient à user2
        $ownerAfter = (int)$r['user2_id'];
        $ownerBefore = (int)$r['user1_id'];
        $prevUsername = $r['user1_name'];
        $prevPdp = $r['user1_pdp'] ?? 'default.png';
      } else {
        // L'objet était objet2 : après échange il appartient à user1
        $ownerAfter = (int)$r['user1_id'];
        $ownerBefore = (int)$r['user2_id'];
        $prevUsername = $r['user2_name'];
        $prevPdp = $r['user2_pdp'] ?? 'default.png';
      }

      // Ajouter le propriétaire précédent à la timeline
      $timeline[] = [
        'user_id' => $ownerBefore,
        'username' => $prevUsername,
        'pdp' => $prevPdp,
        'date' => $r['date_echange'],
        'note' => 'Transféré'
      ];

      $trackingOwner = $ownerBefore;
    }

    // Inverser pour ordre chronologique (ancien -> actuel)
    return array_reverse($timeline);
  }

}
