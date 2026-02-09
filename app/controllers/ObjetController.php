<?php
class ObjetController
{
    public static function list()
    {
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $photoService = new PhotoService($pdo);
        $objets = $repo->findByUserId(2);

        // Ajouter la première photo à chaque objet
        foreach ($objets as &$objet) {
            $objet['photo'] = $photoService->getFirstPhoto($objet['id']);
        }

        Flight::render('objet/liste', ['objets' => $objets]);
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
        Flight::render('objet/detail', ['objet' => $objet]);
    }

    public static function showForm()
    {
        $pdo = Flight::db();
        $repo = new CategoryRepository($pdo);
        $categories = $repo->findAll();
        Flight::render('objet/formulaire', ['categories' => $categories]);
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
            'user_id' => 2 // À récupérer depuis la session plus tard
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

        Flight::render('objet/formulaire', ['objet' => $objet, 'categories' => $categories]);
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
}
