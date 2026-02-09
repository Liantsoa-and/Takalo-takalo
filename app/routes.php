<?php
require_once __DIR__ . '/controllers/AuthController.php';
<<<<<<< HEAD
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/repositories/UserRepository.php';  

=======
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/repositories/ObjetRepository.php';
>>>>>>> d8c3faee0c74e730f670a968f954dd24f01ae6ab


Flight::route('/', function () {
    Flight::redirect('/login');
});

// Login routes
Flight::route('GET /login', function () {
    AuthController::showLogin();
});
Flight::route('POST /login', function () {
    AuthController::postLogin();
});

<<<<<<< HEAD
//message routes
=======
// Objets routes
Flight::route('GET /objet/@id', function ($id) {
    ObjetController::detail($id);
});


Flight::route('GET /objets', function () {
    ObjetController::list();
});
>>>>>>> d8c3faee0c74e730f670a968f954dd24f01ae6ab

Flight::route('GET /admin_users', ['AdminController', 'showUsers']);


// Flight::route('POST /register', ['AuthController', 'postRegister']);



