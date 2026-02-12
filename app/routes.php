<?php
require_once __DIR__ . '/controllers/AuthController.php';

require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/CategoryRepository.php';
require_once __DIR__ . '/controllers/ObjetController.php';
require_once __DIR__ . '/controllers/EchangeController.php';
require_once __DIR__ . '/repositories/ObjetRepository.php';
require_once __DIR__ . '/repositories/EchangeRepository.php';
require_once __DIR__ . '/controllers/ProfilController.php';


require_once __DIR__ . '/services/PhotoService.php';
require_once __DIR__ . '/services/Validator.php';
require_once __DIR__ . '/services/UserService.php';


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
Flight::route('GET /logout', function () {
    AuthController::logout();
});

// Register routes
Flight::route('GET /register', function () {
    AuthController::showRegister();
});
Flight::route('POST /register', function () {
    AuthController::postRegister();
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

// Historique d'appartenance d'un objet
Flight::route('GET /objet/@id/history', ['ObjetController', 'history']);

Flight::route('GET /objets', function () {
    ObjetController::list();
});

Flight::route('GET /objets_publics', function () {
    ObjetController::listeObjetPublics();
});

// AJAX search endpoints
Flight::route('GET /objets/search', ['ObjetController', 'searchMine']);
Flight::route('GET /objets_publics/search', ['ObjetController', 'searchPublics']);

Flight::route('GET /photo/@id/delete', function ($id) {
    ObjetController::deletePhoto($id);
});

// Echange routes
Flight::route('POST /echange/propose', function () {
    ObjetController::proposeEchange();
});

Flight::route('GET /echanges', function () {
    EchangeController::mesEchanges();
});

Flight::route('POST /echange/@id/accepter', function ($id) {
    EchangeController::accepter($id);
});

Flight::route('POST /echange/@id/refuser', function ($id) {
    EchangeController::refuser($id);
});

// Admin routes----------------------------------------------
Flight::route('GET /admin', function () {
    Flight::redirect('/admin/dashboard');
});

Flight::route('GET /admin/dashboard', ['AdminController', 'showDashboard']);

Flight::route('GET /admin/users', ['AdminController', 'showUsers']);

Flight::route('GET /admin/objets', ['AdminController', 'showObjets']);

Flight::route('GET /admin/@id/users', function ($id) {
    AdminController::showUsersById($id);
});

// API for users (AJAX)
Flight::route('POST /admin/user/create', ['AdminController', 'apiCreateUser']);
Flight::route('GET /admin/user/@id', ['AdminController', 'apiGetUser']);
Flight::route('POST /admin/user/@id/update', ['AdminController', 'apiUpdateUser']);
Flight::route('GET /admin/user/@id/delete', ['AdminController', 'apiDeleteUser']);

Flight::route('GET /admin/echanges', ['AdminController', 'showEchanges']);

Flight::route('GET /admin/objet/@id/delete', ['AdminController', 'apiDeleteObjet']);

// Admin Categories
Flight::route('GET /admin/categories', ['AdminController', 'showCategories']);
Flight::route('POST /admin/category/create', ['AdminController', 'apiCreateCategory']);
Flight::route('GET /admin/category/@id', ['AdminController', 'apiGetCategory']);
Flight::route('POST /admin/category/@id/update', ['AdminController', 'apiUpdateCategory']);
Flight::route('GET /admin/category/@id/delete', ['AdminController', 'apiDeleteCategory']);
Flight::route('POST /admin/category/@id/migrate-delete', ['AdminController', 'apiMigrateAndDeleteCategory']);


// Profil 
Flight::route('GET /profil/@id/@typePersonne',function($id, $typePersonne){
    ProfilController::show($id,$typePersonne);
});



