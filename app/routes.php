<?php
require_once __DIR__ . '/controllers/AuthController.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/repositories/ObjetRepository.php';
require_once __DIR__ . '/services/PhotoService.php';


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
Flight::route('GET /objet/formulaire', function () {
    ObjetController::showForm();
});

Flight::route('POST /objet/create', function () {
    ObjetController::create();
});

Flight::route('GET /objet/@id/edit', function ($id) {
    ObjetController::editForm($id);
});

Flight::route('POST /objet/@id/update', function ($id) {
    ObjetController::update($id);
});

Flight::route('GET /objet/@id/delete', function ($id) {
    ObjetController::delete($id);
});

Flight::route('GET /objet/@id', function ($id) {
    ObjetController::detail($id);
});

Flight::route('GET /objets', function () {
    ObjetController::list();
});

Flight::route('GET /photo/@id/delete', function ($id) {
    ObjetController::deletePhoto($id);
});

Flight::route('GET /admin_users', ['AdminController', 'showUsers']);


// Flight::route('POST /register', ['AuthController', 'postRegister']);



