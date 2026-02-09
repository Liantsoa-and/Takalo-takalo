<?php
require_once __DIR__ . '/controllers/AuthController.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/repositories/UserRepository.php';  

require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/repositories/ObjetRepository.php';


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

//message routes

Flight::route('GET /admin_users', ['AdminController', 'showUsers']);


// Flight::route('POST /register', ['AuthController', 'postRegister']);



