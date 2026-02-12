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
            LEFT JOIN tt_objets o1 ON e.objet1_id = o1.id
            LEFT JOIN tt_users u1 ON o1.user_id = u1.id
            LEFT JOIN tt_objets o2 ON e.objet2_id = o2.id
            LEFT JOIN tt_users u2 ON o2.user_id = u2.id
            LEFT JOIN tt_status s ON e.status_id = s.id
            ORDER BY e.id DESC";
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Créer une nouvelle proposition d'échange
  public function createEchange($objet1_id, $objet2_id)
  {
    // Statut par défaut : "en attente" (id = 1)
    $sql = "INSERT INTO tt_echanges (objet1_id, objet2_id, status_id) VALUES (?, ?, 1)";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $objet1_id, (int) $objet2_id]);
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

  // Récupérer les échanges proposés pour un objet spécifique
  public function getEchangesByObjetId($objet_id)
  {
    $sql = "SELECT e.id,
                   o1.id as objet1_id, o1.libelle AS objet1,
                   u1.username AS user1,
                   o2.id as objet2_id, o2.libelle AS objet2,
                   u2.username AS user2,
                   s.libelle AS status,
                   e.date_echange
            FROM tt_echanges e
            LEFT JOIN tt_objets o1 ON e.objet1_id = o1.id
            LEFT JOIN tt_users u1 ON o1.user_id = u1.id
            LEFT JOIN tt_objets o2 ON e.objet2_id = o2.id
            LEFT JOIN tt_users u2 ON o2.user_id = u2.id
            LEFT JOIN tt_status s ON e.status_id = s.id
            WHERE e.objet1_id = ? OR e.objet2_id = ?
            ORDER BY e.date_echange DESC";
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
                   u1.id as proposant_id,
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
            INNER JOIN tt_users u1 ON o1.user_id = u1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            WHERE o2.user_id = ?
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
                   u2.id as destinataire_id,
                   u2.username AS destinataire,
                   s.libelle AS status,
                   e.date_echange,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o1.id LIMIT 1) as ma_photo,
                   (SELECT url FROM tt_photos_objet WHERE objet_id = o2.id LIMIT 1) as photo_cible
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            INNER JOIN tt_users u2 ON o2.user_id = u2.id
            INNER JOIN tt_status s ON e.status_id = s.id
            WHERE o1.user_id = ?
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
            LEFT JOIN tt_objets o1 ON e.objet1_id = o1.id
            LEFT JOIN tt_users u1 ON o1.user_id = u1.id
            LEFT JOIN tt_objets o2 ON e.objet2_id = o2.id
            LEFT JOIN tt_users u2 ON o2.user_id = u2.id
            LEFT JOIN tt_status s ON e.status_id = s.id
            ORDER BY e.id DESC
            LIMIT " . (int)$limit;
    $st = $this->pdo->query($sql);
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  // Récupérer un échange par ID
  public function findById($id)
  {
    $sql = "SELECT e.*, 
                   o1.user_id as user1_id,
                   o2.user_id as user2_id
            FROM tt_echanges e
            INNER JOIN tt_objets o1 ON e.objet1_id = o1.id
            INNER JOIN tt_objets o2 ON e.objet2_id = o2.id
            WHERE e.id = ?";
    $st = $this->pdo->prepare($sql);
    $st->execute([(int) $id]);
    return $st->fetch(PDO::FETCH_ASSOC);
  }

  // Récupérer l'historique d'appartenance d'un objet (liste des propriétaires dans le temps)
  public function getOwnershipHistory($objetId)
  {
    // Récupérer l'état courant (propriétaire actuel)
    $st = $this->pdo->prepare("SELECT user_id FROM tt_objets WHERE id = ? LIMIT 1");
    $st->execute([(int)$objetId]);
    $current = $st->fetch(PDO::FETCH_ASSOC);
    $currentOwner = $current ? (int)$current['user_id'] : null;

    // Récupérer les échanges confirmés impliquant cet objet, par date décroissante
    $sql = "SELECT e.id, e.date_echange, e.objet1_id, e.objet2_id,
                   o1.user_id AS o1_user_id, u1.username AS o1_username, u1.pdp AS o1_pdp,
                   o2.user_id AS o2_user_id, u2.username AS o2_username, u2.pdp AS o2_pdp,
                   s.libelle AS status
            FROM tt_echanges e
            LEFT JOIN tt_objets o1 ON e.objet1_id = o1.id
            LEFT JOIN tt_users u1 ON o1.user_id = u1.id
            LEFT JOIN tt_objets o2 ON e.objet2_id = o2.id
            LEFT JOIN tt_users u2 ON o2.user_id = u2.id
            LEFT JOIN tt_status s ON e.status_id = s.id
            WHERE (e.objet1_id = ? OR e.objet2_id = ?) AND e.status_id = 2
            ORDER BY e.date_echange DESC";

    $st = $this->pdo->prepare($sql);
    $st->execute([(int)$objetId, (int)$objetId]);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    $timeline = [];
    if ($currentOwner !== null) {
      // récupérer infos utilisateur courant
      $ust = $this->pdo->prepare("SELECT id, username, pdp FROM tt_users WHERE id = ? LIMIT 1");
      $ust->execute([$currentOwner]);
      $u = $ust->fetch(PDO::FETCH_ASSOC) ?: ['id'=>$currentOwner,'username'=>'Utilisateur','pdp'=>'default.png'];
      $timeline[] = ['user_id'=>$u['id'],'username'=>$u['username'],'pdp'=>$u['pdp'],'date'=>null,'note'=>'Actuel'];
    }

    // parcourir les échanges du plus récent au plus ancien et reconstituer les propriétaires précédents
    foreach ($rows as $r) {
      // déterminer l'autre utilisateur impliqué dans l'échange
      if ((int)$r['objet1_id'] === (int)$objetId) {
        $userAfter = (int)$r['o2_user_id']; // après échange, objet1 appartient à o2
        $userBefore = (int)$r['o1_user_id'];
      } else {
        // objet2
        $userAfter = (int)$r['o1_user_id']; // après échange, objet2 appartient à o1
        $userBefore = (int)$r['o2_user_id'];
      }

      // si le propriétaire courant correspond à l'état après l'échange, le propriétaire précédent est userBefore
      $prevOwner = ($currentOwner === $userAfter) ? $userBefore : $userAfter;

      // récupérer infos du prevOwner
      $ust = $this->pdo->prepare("SELECT id, username, pdp FROM tt_users WHERE id = ? LIMIT 1");
      $ust->execute([$prevOwner]);
      $pu = $ust->fetch(PDO::FETCH_ASSOC) ?: ['id'=>$prevOwner,'username'=>'Utilisateur','pdp'=>'default.png'];

      $timeline[] = ['user_id'=>$pu['id'],'username'=>$pu['username'],'pdp'=>$pu['pdp'],'date'=>$r['date_echange'],'note'=>'Transféré'];

      // mettre à jour currentOwner pour l'étape suivante (remonter dans le temps)
      $currentOwner = $prevOwner;
    }

    // inverser pour ordre chronologique (ancien -> actuel)
    return array_reverse($timeline);
  }

}
