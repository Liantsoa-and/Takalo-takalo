<?php
require_once __DIR__ . '/controllers/AuthController.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/repositories/UserRepository.php';  

require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/repositories/ObjetRepository.php';
require_once __DIR__ . '/repositories/EchangeRepository.php';




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

// Objets routes
Flight::route('GET /objet/@id', function ($id) {
    ObjetController::detail($id);
});


Flight::route('GET /objets', function () {
    ObjetController::list();
});

Flight::route('GET /admin_users', ['AdminController', 'showUsers']);

Flight::route('GET /admin_echange', ['AdminController', 'showEchanges']);





// Flight::route('POST /register', ['AuthController', 'postRegister']);



