<?php
class AdminController
{

public static function showUsers()
    {
        $pdo = Flight::db();
        $repo = new UserRepository($pdo);
        $users = $repo->findAll();
        Flight::render('admin/users', ['users' => $users]);
    }

}
