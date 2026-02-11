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

}