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
        $data = Flight::request()->data->getData();
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $id = $repo->createFull($data);
        Flight::json(['success' => true, 'id' => $id]);
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
        $data = Flight::request()->data->getData();
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $ok = $repo->update($id, $data);
        Flight::json(['success' => (bool)$ok]);
    }

public static function apiDeleteUser($id)
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $ok = $repo->delete($id);
        Flight::json(['success' => (bool)$ok]);
    }

}