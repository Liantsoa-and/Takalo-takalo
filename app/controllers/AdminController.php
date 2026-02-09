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
}

