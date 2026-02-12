<?php
class AdminController
{

public static function showUsers()
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $users = $repo->findAll();
        $utilisateur = $repo->findAllUtilisateur();
        $pagename = "admin/users.php";
        Flight::render('admin/modele', ['users' => $users, 'utilisateur' => $utilisateur, 'pagename' => $pagename]);
    }

public static function showEchanges()
    {
        $pdo = Flight::db();
        $repo = new EchangeRepository($pdo);
        $echanges = $repo->findAll();
        $pagename = "admin/echanges.php";
        Flight::render('admin/modele', ['echanges' => $echanges, 'pagename' => $pagename]);
}

public static function showUsersById($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $user = $repo->findById($id);
        $pagename = "admin/user.php";
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

        $pagename = "admin/dashboard.php";
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

        $pagename = "admin/objets.php";
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

// ============ CATÉGORIES ADMIN ============

public static function showCategories()
    {
        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);
        $categories = $repo->findAll();

        // Compter les objets par catégorie
        foreach ($categories as &$cat) {
            $cat['nb_objets'] = $repo->countObjetsByCategory($cat['id']);
        }
        unset($cat);

        $pagename = "admin/categories.php";
        Flight::render('admin/modele', ['categories' => $categories, 'pagename' => $pagename]);
    }

public static function apiCreateCategory()
    {
        $data = Flight::request()->data->getData();
        $libelle = trim($data['libelle'] ?? '');

        if ($libelle === '') {
            Flight::json(['success' => false, 'message' => 'Le nom de la catégorie est obligatoire.']);
            return;
        }

        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);
        $id = $repo->create(['libelle' => $libelle]);
        Flight::json(['success' => true, 'id' => $id, 'libelle' => $libelle]);
    }

public static function apiGetCategory($id)
    {
        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);
        $cat = $repo->findById($id);
        if ($cat) {
            $cat['nb_objets'] = $repo->countObjetsByCategory($cat['id']);
            Flight::json(['success' => true, 'category' => $cat]);
        } else {
            Flight::json(['success' => false, 'message' => 'Catégorie introuvable.']);
        }
    }

public static function apiUpdateCategory($id)
    {
        $data = Flight::request()->data->getData();
        $libelle = trim($data['libelle'] ?? '');

        if ($libelle === '') {
            Flight::json(['success' => false, 'message' => 'Le nom de la catégorie est obligatoire.']);
            return;
        }

        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);

        $cat = $repo->findById($id);
        if (!$cat) {
            Flight::json(['success' => false, 'message' => 'Catégorie introuvable.']);
            return;
        }

        $repo->update($id, ['libelle' => $libelle]);
        Flight::json(['success' => true]);
    }

public static function apiDeleteCategory($id)
    {
        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);

        $cat = $repo->findById($id);
        if (!$cat) {
            Flight::json(['success' => false, 'message' => 'Catégorie introuvable.']);
            return;
        }

        $nbObjets = $repo->countObjetsByCategory($id);

        if ($nbObjets > 0) {
            Flight::json([
                'success' => false,
                'used' => true,
                'nb_objets' => $nbObjets,
                'message' => "Cette catégorie est utilisée par $nbObjets objet(s). Veuillez choisir une catégorie de remplacement."
            ]);
            return;
        }

        $repo->deleteById($id);
        Flight::json(['success' => true]);
    }

public static function apiMigrateAndDeleteCategory($id)
    {
        $data = Flight::request()->data->getData();
        $targetId = (int)($data['target_category_id'] ?? 0);

        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);

        $cat = $repo->findById($id);
        if (!$cat) {
            Flight::json(['success' => false, 'message' => 'Catégorie introuvable.']);
            return;
        }

        if ($targetId <= 0 || $targetId == $id) {
            Flight::json(['success' => false, 'message' => 'Catégorie de remplacement invalide.']);
            return;
        }

        $target = $repo->findById($targetId);
        if (!$target) {
            Flight::json(['success' => false, 'message' => 'Catégorie de remplacement introuvable.']);
            return;
        }

        $repo->migrateObjets($id, $targetId);
        $repo->deleteById($id);

        Flight::json(['success' => true, 'message' => 'Objets migrés et catégorie supprimée.']);
    }


    // ============ MEMBERS (Membres du projet) ============

    public static function showMembres()
    {
        $pdo = Flight::db();
        require_once __DIR__ . '/../repositories/MemberRepository.php';
        $repo = new MemberRepository($pdo);
        $membres = $repo->findAll();
        $pagename = "admin/membres.php";
        Flight::render('admin/modele', ['membres' => $membres, 'pagename' => $pagename]);
    }

    public static function apiCreateMembre()
    {
        $data = $_POST ?: Flight::request()->data->getData();

        // handle uploaded photo (store filename in DB, file in public/uploads/membres)
        $photoFilename = null;
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['photo']['tmp_name'];
            $orig = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destDir = realpath(__DIR__ . '/../../public/uploads/membres');
                if (!$destDir) {
                    mkdir(__DIR__ . '/../../public/uploads/membres', 0755, true);
                    $destDir = realpath(__DIR__ . '/../../public/uploads/membres');
                }
                if ($destDir && move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $name)) {
                    $photoFilename = $name;
                }
            }
        }

        if ($photoFilename) $data['photo'] = $photoFilename;

        $pdo = Flight::db();
        require_once __DIR__ . '/../repositories/MemberRepository.php';
        $repo = new MemberRepository($pdo);
        $id = $repo->create($data);
        Flight::json(['success' => true, 'id' => $id, 'photo' => $photoFilename]);
    }

    public static function apiDeleteMembre($id)
    {
        $pdo = Flight::db();
        require_once __DIR__ . '/../repositories/MemberRepository.php';
        $repo = new MemberRepository($pdo);
        $ok = $repo->delete($id);
        Flight::json(['success' => (bool)$ok]);
    }

}