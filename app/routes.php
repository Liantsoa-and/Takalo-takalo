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
    AuthController::requireLogin();
    ObjetController::showForm();
});

Flight::route('POST /objet/create', function () {
    AuthController::requireLogin();
    ObjetController::create();
});

Flight::route('GET /objet/@id/edit', function ($id) {
    AuthController::requireLogin();
    ObjetController::editForm($id);
});

Flight::route('POST /objet/@id/update', function ($id) {
    AuthController::requireLogin();
    ObjetController::update($id);
});

Flight::route('GET /objet/@id/delete', function ($id) {
    AuthController::requireLogin();
    ObjetController::delete($id);
});

Flight::route('GET /objet/@id', function ($id) {
    AuthController::requireLogin();
    ObjetController::detail($id);
});

// Historique d'appartenance d'un objet
Flight::route('GET /objet/@id/history', function ($id) {
    AuthController::requireLogin();
    ObjetController::history($id);
});

Flight::route('GET /objets', function () {
    AuthController::requireLogin();
    ObjetController::list();
});

Flight::route('GET /objets_publics', function () {
    AuthController::requireLogin();
    ObjetController::listeObjetPublics();
});

// AJAX search endpoints
Flight::route('GET /objets/search', function () {
    AuthController::requireLogin();
    ObjetController::searchMine();
});
Flight::route('GET /objets_publics/search', function () {
    AuthController::requireLogin();
    ObjetController::searchPublics();
});

Flight::route('GET /photo/@id/delete', function ($id) {
    AuthController::requireLogin();
    ObjetController::deletePhoto($id);
});

// Echange routes
Flight::route('POST /echange/propose', function () {
    AuthController::requireLogin();
    ObjetController::proposeEchange();
});

Flight::route('GET /echanges', function () {
    AuthController::requireLogin();
    EchangeController::mesEchanges();
});

Flight::route('POST /echange/@id/accepter', function ($id) {
    AuthController::requireLogin();
    EchangeController::accepter($id);
});

Flight::route('POST /echange/@id/refuser', function ($id) {
    AuthController::requireLogin();
    EchangeController::refuser($id);
});

// Admin routes----------------------------------------------
Flight::route('GET /admin', function () {
    Flight::redirect('/admin/dashboard');
});

Flight::route('GET /admin/dashboard', function () {
    AuthController::requireLogin('admin');
    AdminController::showDashboard();
});

Flight::route('GET /admin/users', function () {
    AuthController::requireLogin('admin');
    AdminController::showUsers();
});

Flight::route('GET /admin/objets', function () {
    AuthController::requireLogin('admin');
    AdminController::showObjets();
});

Flight::route('GET /admin/@id/users', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::showUsersById($id);
});

// API for users (AJAX)
Flight::route('POST /admin/user/create', function () {
    AuthController::requireLogin('admin');
    AdminController::apiCreateUser();
});
Flight::route('GET /admin/user/@id', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiGetUser($id);
});
Flight::route('POST /admin/user/@id/update', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiUpdateUser($id);
});
Flight::route('GET /admin/user/@id/delete', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiDeleteUser($id);
});

Flight::route('GET /admin/echanges', function () {
    AuthController::requireLogin('admin');
    AdminController::showEchanges();
});

Flight::route('GET /admin/objet/@id/delete', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiDeleteObjet($id);
});

// Admin Categories
Flight::route('GET /admin/categories', function () {
    AuthController::requireLogin('admin');
    AdminController::showCategories();
});
Flight::route('POST /admin/category/create', function () {
    AuthController::requireLogin('admin');
    AdminController::apiCreateCategory();
});
Flight::route('GET /admin/category/@id', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiGetCategory($id);
});
Flight::route('POST /admin/category/@id/update', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiUpdateCategory($id);
});
Flight::route('GET /admin/category/@id/delete', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiDeleteCategory($id);
});
Flight::route('POST /admin/category/@id/migrate-delete', function ($id) {
    AuthController::requireLogin('admin');
    AdminController::apiMigrateAndDeleteCategory($id);
});


// Profil 
Flight::route('GET /profil/@id/@typePersonne',function($id, $typePersonne){
    AuthController::requireLogin();
    ProfilController::show($id,$typePersonne);
});



