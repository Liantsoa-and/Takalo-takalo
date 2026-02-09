<?php
class ObjetController
{
    public static function list(){
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $objets = $repo->findByUserId(2);
        Flight::render('objet/liste', ['objets' => $objets]);
    }

    public static function detail($id){
        $pdo = Flight::db();
        $repo = new ObjetRepository($pdo);
        $objet = $repo->findById($id);
        if (!$objet) {
            Flight::notFound();
            return;
        }
        Flight::render('objet/detail', ['objet' => $objet]);
    }



}
