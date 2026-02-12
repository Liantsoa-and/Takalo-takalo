<?php
class ObjetController
{
    public static function list()
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);
        $objets = $repo->findByUserId(3);

        // Ajouter la première photo à chaque objet
        foreach ($objets as &$objet) {
            $objet['photo'] = $photoService->getFirstPhoto($objet['id']);
        }

        // Récupérer les catégories pour le sélecteur de recherche
        $catRepo = new CategoryRepository($pdo);
        $categories = $catRepo->findAll();

        $pagename = "objet/liste.php";
        Flight::render('modele', ['objets' => $objets, 'categories' => $categories, 'pagename' => $pagename]);
    }

    public static function detail($id)
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);
        $objet = $repo->findById($id);
        if (!$objet) {
            Flight::notFound();
            return;
        }
        $objet['photos'] = $photoService->getPhotosByObjetId($id);

        // Récupérer les objets de l'utilisateur connecté pour proposition d'échange
        $currentUserId = 3; // À récupérer depuis la session plus tard
        $objetRepository = new ObjetRepository($pdo);
        $mesObjets = $objetRepository->findByUserId($currentUserId);

        // Récupérer les échanges liés à cet objet
        $echangeRepo = new EchangeRepository($pdo);
        $echanges = $echangeRepo->getEchangesByObjetId($id);

        $pagename = "objet/detail.php";

        Flight::render('modele', [
            'objet' => $objet,
            'mesObjets' => $mesObjets,
            'echanges' => $echanges,
            'currentUserId' => $currentUserId,
            'pagename' => $pagename
        ]);
    }

    public static function showForm()
    {
        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);
        $categories = $repo->findAll();

        $pagename = "objet/formulaire.php";
        Flight::render('modele', ['categories' => $categories, 'pagename' => $pagename]);
    }

    public static function create()
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        $data = [
            'libelle' => $_POST['libelle'] ?? '',
            'description' => $_POST['description'] ?? '',
            'category_id' => $_POST['category_id'] ?? 0,
            'prix_estimatif' => $_POST['prix_estimatif'] ?? 0,
            'user_id' => 1 // À récupérer depuis la session plus tard
        ];

        $id = $repo->create($data);

        // Upload des photos
        if (isset($_FILES['photos'])) {
            $photoService->uploadPhotos($id, $_FILES['photos']);
        }

        Flight::redirect('/objet/' . $id);
    }

    public static function update($id)
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        $objet = $repo->findById($id);
        if (!$objet) {
            Flight::notFound();
            return;
        }

        $data = [
            'libelle' => $_POST['libelle'] ?? '',
            'description' => $_POST['description'] ?? '',
            'category_id' => $_POST['category_id'] ?? 0,
            'prix_estimatif' => $_POST['prix_estimatif'] ?? 0,
        ];

        $repo->update($id, $data);

        // Upload des nouvelles photos
        if (isset($_FILES['photos']) && $_FILES['photos']['error'][0] !== UPLOAD_ERR_NO_FILE) {
            $photoService->uploadPhotos($id, $_FILES['photos']);
        }

        Flight::redirect('/objet/' . $id);
    }

    public static function editForm($id)
    {
        $pdo = Flight::db();
        $repo1 = new ObjetRepository($pdo);
        $objet = $repo1->findById($id);
        $repo2 = new CategoryRepository($pdo);
        $categories = $repo2->findAll();

        if (!$objet) {
            Flight::notFound();
            return;
        }

        $photoService = new PhotoService($pdo);
        $objet['photos'] = $photoService->getPhotosByObjetId($id);
        $pagename = "objet/formulaire.php";

        Flight::render('modele', ['objet' => $objet, 'categories' => $categories, 'pagename' => $pagename]);
    }

    public static function delete($id)
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        // Supprimer les photos
        $photoService->deletePhotosByObjetId($id);

        // Supprimer l'objet
        $repo->deleteById($id);
        Flight::redirect('/objets');
    }

    public static function deletePhoto($id)
    {
        $pdo = Flight::db();
        $photoService = new PhotoService($pdo);

        // Récupérer l'ID de l'objet pour rediriger après
        $st = $pdo->prepare("SELECT objet_id FROM tt_photos_objet WHERE id = ?");
        $st->execute([(int) $id]);
        $photo = $st->fetch(PDO::FETCH_ASSOC);

        if (!$photo) {
            Flight::notFound();
            return;
        }

        $objetId = $photo['objet_id'];
        $photoService->deletePhoto($id);
        Flight::redirect('/objet/' . $objetId . '/edit');
    }

    public static function listeObjetPublics()
    {
        $currentUserId = null; // À récupérer depuis la session plus tard
        $categoryId = $_GET['category_id'] ?? null;
        $page = $_GET['page'] ?? 1;
        $limit = 10;

        $pdo = Flight::db();
        $objetRepository = new ObjetRepository($pdo);
        $objets = $objetRepository->getObjetsByOthers($currentUserId, $categoryId, $page, $limit);
        $total = $objetRepository->countObjetsByOthers($currentUserId, $categoryId);
        $totalPages = ceil($total / $limit);

        $categoryRepository = new CategoryRepository($pdo);
        $categories = $categoryRepository->findAll();
        $pagename = "objet/publics.php";

        Flight::render('modele', [
            'objets' => $objets,
            'categories' => $categories,
            'currentCategory' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'pagename' => $pagename
        ]);
    }

    // AJAX search for user's own objects
    public static function searchMine()
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        $q = $_GET['q'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        $currentUserId = 3; // TODO: retrieve from session

        $objets = $repo->searchByUser($currentUserId, $q, $categoryId);
        // attach main photo
        foreach ($objets as &$o) {
            $o['main_photo'] = $photoService->getFirstPhoto($o['id']);
        }

        // If AJAX, render partial cards
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            Flight::render('objet/_cards', ['objets' => $objets, 'scope' => 'mine']);
            return;
        }

        // fallback: render full page
        $pagename = 'objet/liste.php';
        Flight::render('modele', ['objets' => $objets, 'pagename' => $pagename]);
    }

    // AJAX search for public objects
    public static function searchPublics()
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        $q = $_GET['q'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        $currentUserId = null; // TODO: session

        $objets = $repo->searchPublic($currentUserId, $q, $categoryId);
        foreach ($objets as &$o) {
            $o['main_photo'] = $photoService->getFirstPhoto($o['id']);
        }

        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            Flight::render('objet/_cards', ['objets' => $objets, 'scope' => 'public']);
            return;
        }

        $pagename = 'objet/publics.php';
        Flight::render('modele', ['objets' => $objets, 'pagename' => $pagename]);
    }

    // Afficher l'historique d'appartenance pour un objet
    public static function history($id)
    {
        $pdo = Flight::db();
        $objetRepo = new ObjetRepository($pdo);
        $objet = $objetRepo->findById($id);
        if (!$objet) {
            Flight::notFound();
            return;
        }

        $echangeRepo = new EchangeRepository($pdo);
        $timeline = $echangeRepo->getOwnershipHistory($id);

        $pagename = 'objet/history.php';
        Flight::render('modele', [
            'objet' => $objet,
            'timeline' => $timeline,
            'pagename' => $pagename
        ]);
    }

    // Proposer un échange
    public static function proposeEchange()
    {
        $currentUserId = 3; // À récupérer depuis la session plus tard

        $objet2_id = $_POST['objet2_id'] ?? null; // L'objet ciblé (celui qu'on veut)
        $objet1_id = $_POST['objet1_id'] ?? null; // Mon objet (celui qu'on propose)

        if (!$objet2_id || !$objet1_id) {
            Flight::json(['error' => 'Paramètres manquants'], 400);
            return;
        }

        $pdo = Flight::db();
        $objetRepo = new ObjetRepository($pdo);
        $echangeRepo = new EchangeRepository($pdo);

        // Vérifier que l'objet proposé appartient bien à l'utilisateur connecté
        $monObjet = $objetRepo->findById($objet1_id);
        if (!$monObjet || $monObjet['user_id'] != $currentUserId) {
            Flight::json(['error' => 'Cet objet ne vous appartient pas'], 403);
            return;
        }

        // Vérifier que l'objet ciblé existe et n'appartient pas à l'utilisateur
        $objetCible = $objetRepo->findById($objet2_id);
        if (!$objetCible) {
            Flight::json(['error' => 'Objet ciblé introuvable'], 404);
            return;
        }

        if ($objetCible['user_id'] == $currentUserId) {
            Flight::json(['error' => 'Vous ne pouvez pas proposer un échange avec votre propre objet'], 400);
            return;
        }

        // Vérifier qu'un échange n'existe pas déjà
        if ($echangeRepo->echangeExists($objet1_id, $objet2_id)) {
            Flight::json(['error' => 'Un échange existe déjà entre ces deux objets'], 400);
            return;
        }

        // Créer la proposition d'échange
        $echangeId = $echangeRepo->createEchange($objet1_id, $objet2_id);

        // Rediriger vers la page de détail de l'objet
        Flight::redirect('/objet/' . $objet2_id . '?success=echange_propose');
    }
}
