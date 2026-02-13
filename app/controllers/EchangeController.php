<?php
class EchangeController
{
    private static function currentUserId(): int
    {
        return (int)($_SESSION['user_id'] ?? 0);
    }

    // Page de gestion des échanges
    public static function mesEchanges()
    {
        $currentUserId = self::currentUserId();
        $onglet = $_GET['onglet'] ?? 'recues'; // Par défaut : propositions reçues

        $pdo = Flight::db();
        $echangeRepo = new EchangeRepository($pdo);

        // Récupérer les propositions selon l'onglet actif
        if ($onglet === 'envoyees') {
            $echanges = $echangeRepo->getPropositionsEnvoyees($currentUserId);
        } else {
            $echanges = $echangeRepo->getPropositionsRecues($currentUserId);
        }

        $pagename = "echange/liste.php";

        Flight::render('modele', [
            'echanges' => $echanges,
            'onglet' => $onglet,
            'currentUserId' => $currentUserId,
            'pagename' => $pagename
        ]);
    }

    // Accepter une proposition d'échange
    public static function accepter($id)
    {
        $currentUserId = self::currentUserId();

        $pdo = Flight::db();
        $echangeRepo = new EchangeRepository($pdo);

        // Vérifier que l'échange existe et que l'utilisateur est bien le destinataire
        $echange = $echangeRepo->findById($id);

        if (!$echange) {
            Flight::json(['error' => 'Échange non trouvé'], 404);
            return;
        }

        // Vérifier que c'est bien l'utilisateur qui reçoit la proposition
        if ($echange['user2_id'] != $currentUserId) {
            Flight::json(['error' => 'Vous n\'êtes pas autorisé à accepter cet échange'], 403);
            return;
        }

        // Vérifier que l'échange est en attente
        if ($echange['status_id'] != 1) {
            Flight::json(['error' => 'Cet échange n\'est plus en attente'], 400);
            return;
        }

        // Accepter l'échange
        $success = $echangeRepo->accepterEchange($id);

        if ($success) {
            Flight::redirect('/echanges?onglet=recues&success=accepte');
        } else {
            Flight::redirect('/echanges?onglet=recues&error=erreur');
        }
    }

    // Refuser une proposition d'échange
    public static function refuser($id)
    {
        $currentUserId = self::currentUserId();

        $pdo = Flight::db();
        $echangeRepo = new EchangeRepository($pdo);

        // Vérifier que l'échange existe et que l'utilisateur est bien le destinataire
        $echange = $echangeRepo->findById($id);

        if (!$echange) {
            Flight::json(['error' => 'Échange non trouvé'], 404);
            return;
        }

        // Vérifier que c'est bien l'utilisateur qui reçoit la proposition
        if ($echange['user2_id'] != $currentUserId) {
            Flight::json(['error' => 'Vous n\'êtes pas autorisé à refuser cet échange'], 403);
            return;
        }

        // Vérifier que l'échange est en attente
        if ($echange['status_id'] != 1) {
            Flight::json(['error' => 'Cet échange n\'est plus en attente'], 400);
            return;
        }

        // Refuser l'échange
        $success = $echangeRepo->refuserEchange($id);

        if ($success) {
            Flight::redirect('/echanges?onglet=recues&success=refuse');
        } else {
            Flight::redirect('/echanges?onglet=recues&error=erreur');
        }
    }
}
