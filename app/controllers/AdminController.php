<?php
class AdminController
{

public static function showUsers()
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $users = $repo->findAll();
        $utilisateur = $repo->findAllUtilisateur();
        $pagename = "users.php";
        Flight::render('admin/modele', ['users' => $users, 'utilisateur' => $utilisateur, 'pagename' => $pagename]);
    }

public static function showEchanges()
    {
        $pdo = Flight::db();
        $repo = new EchangeRepository($pdo);
        $echanges = $repo->findAll();
        $pagename = "echanges.php";
        Flight::render('admin/modele', ['echanges' => $echanges, 'pagename' => $pagename]);
}

public static function showUsersById($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $user = $repo->findById($id);
        $pagename = "user.php";
        Flight::render('admin/modele', ['user' => $user, 'pagename' => $pagename]);
}

public static function apiCreateUser()
    {
        // Accept multipart/form-data (files in $_FILES, fields in $_POST)
        $data = $_POST ?: Flight::request()->data->getData();
        // handle uploaded profile picture
        $pdpFilename = null;
        if (!empty($_FILES['pdp']) && $_FILES['pdp']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['pdp']['tmp_name'];
            $orig = $_FILES['pdp']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destDir = realpath(__DIR__ . '/../../public/assets/images/pdp');
                if ($destDir && move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $name)) {
                    $pdpFilename = $name;
                }
            }
        }
        if ($pdpFilename) $data['pdp'] = $pdpFilename;

        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $id = $repo->createFull($data);
        Flight::json(['success' => true, 'id' => $id, 'pdp' => $pdpFilename]);
    }

public static function apiGetUser($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $user = $repo->findById($id);
        if ($user) {
            Flight::json(['success' => true, 'user' => $user]);
        } else {
            Flight::json(['success' => false, 'user' => null]);
        }
    }

public static function apiUpdateUser($id)
    {
        $data = $_POST ?: Flight::request()->data->getData();
        // handle uploaded profile picture
        $pdpFilename = null;
        if (!empty($_FILES['pdp']) && $_FILES['pdp']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['pdp']['tmp_name'];
            $orig = $_FILES['pdp']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destDir = realpath(__DIR__ . '/../../public/assets/images/pdp');
                if ($destDir && move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $name)) {
                    $pdpFilename = $name;
                }
            }
        }
        if ($pdpFilename) $data['pdp'] = $pdpFilename;

        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $ok = $repo->update($id, $data);
        Flight::json(['success' => (bool)$ok, 'pdp' => $pdpFilename]);
    }

public static function apiDeleteUser($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $ok = $repo->delete($id);
        Flight::json(['success' => (bool)$ok]);
    }

// ============ DASHBOARD ============

public static function showDashboard()
    {
        $pdo = Flight::db();
        $userRepo     = new UserRepository($pdo);
        $objetRepo    = new ObjetRepository($pdo);
        $echangeRepo  = new EchangeRepository($pdo);
        $catRepo      = new CategoryRepository($pdo);

        $nbUsers      = $userRepo->countAll();
        $nbObjets     = $objetRepo->countAll();
        $nbEchanges   = $echangeRepo->countAll();
        $nbCategories = count($catRepo->findAll());

        // Statuts échanges
        $nbEnAttente  = $echangeRepo->countByStatus(1);
        $nbConfirmes  = $echangeRepo->countByStatus(2);
        $nbRefuses    = $echangeRepo->countByStatus(3);

        // Données récentes
        $recentEchanges = $echangeRepo->findRecent(5);
        $recentObjets   = $objetRepo->findRecentWithDetails(5);
        $topUsers       = $userRepo->topUsersByObjets(5);

        $pagename = "dashboard.php";
        Flight::render('admin/modele', [
            'nbUsers'        => $nbUsers,
            'nbObjets'       => $nbObjets,
            'nbEchanges'     => $nbEchanges,
            'nbCategories'   => $nbCategories,
            'nbEnAttente'    => $nbEnAttente,
            'nbConfirmes'    => $nbConfirmes,
            'nbRefuses'      => $nbRefuses,
            'recentEchanges' => $recentEchanges,
            'recentObjets'   => $recentObjets,
            'topUsers'       => $topUsers,
            'pagename'       => $pagename
        ]);
    }

// ============ OBJETS ADMIN ============

public static function showObjets()
    {
        $pdo = Flight::db();
        $objetRepo = new ObjetRepository($pdo);
        $catRepo   = new CategoryRepository($pdo);

        $q          = $_GET['q'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;
        $ownerName  = $_GET['owner'] ?? null;
        $page       = (int)($_GET['page'] ?? 1);
        $limit      = 20;

        $objets     = $objetRepo->findAllWithDetails($q, $categoryId, $ownerName, $page, $limit);
        $total      = $objetRepo->countFiltered($q, $categoryId, $ownerName);
        $totalPages = ceil($total / $limit);
        $categories = $catRepo->findAll();

        $pagename = "objets.php";
        Flight::render('admin/modele', [
            'objets'         => $objets,
            'categories'     => $categories,
            'filterCategory' => $categoryId,
            'filterUser'     => $ownerName,
            'searchQuery'    => $q,
            'currentPage'    => $page,
            'totalPages'     => $totalPages,
            'total'          => $total,
            'pagename'       => $pagename
        ]);
    }

public static function apiDeleteObjet($id)
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);

        $objet = $repo->findById($id);
        if (!$objet) {
            Flight::json(['success' => false, 'message' => 'Objet introuvable']);
            return;
        }

        // Supprimer les photos associées
        $photoService->deletePhotosByObjetId($id);
        // Supprimer l'objet
        $repo->deleteById($id);

        Flight::json(['success' => true]);
    }

}