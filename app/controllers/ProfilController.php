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
}
?>