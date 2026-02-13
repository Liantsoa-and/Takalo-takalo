<?php
class ProfilController
{
    public static function show($id , $typePersonne)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $user = $repo->findById($id);
        if (!$user) {
            Flight::notFound();
            return;
        }
        // Récupérer les objets appartenant à l'utilisateur
        $objetRepo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);
        $objets = $objetRepo->searchByUser($id);
        // Ajouter la photo principale si disponible
        foreach ($objets as &$o) {
            $first = $photoService->getFirstPhoto($o['id']);
            $o['main_photo'] = $first;
        }
        unset($o);

        $namepage = "profil/profil.php";
        
        // Vérifier que seul un admin connecté peut utiliser le contexte admin
        if($typePersonne === 'admin') {
            $connectedUserId = $_SESSION['user_id'] ?? 0;
            $connectedUser = $repo->findById($connectedUserId);
            if (!$connectedUser || $connectedUser['role'] !== 'admin') {
                // Rediriger vers le contexte user si pas admin
                Flight::redirect('/profil/' . $id . '/user');
                return;
            }
            Flight::render('admin/modele', ['user' => $user , 'pagename' => $namepage, 'objets' => $objets]);
            return;
        }
        if($typePersonne === 'user') {
            Flight::render('modele', ['user' => $user , 'pagename' => $namepage, 'objets' => $objets]);
            return;
        }
    }

    public static function postUpdate($id)
    {
        $data = $_POST ?: Flight::request()->data->getData();
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);

        // Only allow owner or admin to update
        $connected = $_SESSION['user_id'] ?? null;
        $connectedRole = $_SESSION['user_role'] ?? null;
        if (!$connected || ($connected != $id && $connectedRole !== 'admin')) {
            Flight::json(['success' => false, 'message' => 'Permission denied']);
            return;
        }

        $updateData = [];
        if (isset($data['username'])) $updateData['username'] = trim($data['username']);
        if (isset($data['password']) && $data['password'] !== '') $updateData['password'] = $data['password'];
        if (isset($data['bio'])) $updateData['bio'] = $data['bio'];

        $ok = $repo->update($id, $updateData);
        if ($ok) {
            Flight::redirect('/profil/' . $id . '/user');
        } else {
            Flight::json(['success' => false]);
        }
    }

    public static function postPhoto($id)
    {
        // handle uploaded profile picture
        $photoFilename = null;
        if (!empty($_FILES['pdp']) && $_FILES['pdp']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['pdp']['tmp_name'];
            $orig = $_FILES['pdp']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $destDir = realpath(__DIR__ . '/../../public/assets/images/pdp');
                if (!$destDir) {
                    mkdir(__DIR__ . '/../../public/assets/images/pdp', 0755, true);
                    $destDir = realpath(__DIR__ . '/../../public/assets/images/pdp');
                }
                if ($destDir && move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $name)) {
                    $photoFilename = $name;
                }
            }
        }

        if (!$photoFilename) {
            Flight::redirect('/profil/' . $id . '/user?error=photo');
            return;
        }

        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $ok = $repo->update($id, ['pdp' => $photoFilename]);
        Flight::redirect('/profil/' . $id . '/user');
    }
}
?>