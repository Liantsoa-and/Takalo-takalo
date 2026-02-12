<?php
class ProfilController
{
    public static function show($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $user = $repo->findById($id);
        if (!$user) {
            Flight::notFound();
            return;
        }
        Flight::render('profil/profil', ['user' => $user]);
    }
}
?>