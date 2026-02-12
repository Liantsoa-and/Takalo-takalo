<?php
class AuthController
{

  public static function showLogin()
  {
    // Démarrer la session si ce n'est pas déjà fait
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
    
    // Si l'utilisateur est déjà connecté, le rediriger
    if (isset($_SESSION['user_id'])) {
      $role = $_SESSION['user_role'] ?? 'user';
      if ($role === 'admin') {
        Flight::redirect('/admin/users');
      } else {
        Flight::redirect('/objets');
      }
      return;
    }
    
    Flight::render('login');
  }

  public static function postLogin()
  {
    // Démarrer la session
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    $pdo = Flight::db();
    $repo = new UserRepository($pdo);

    $req = Flight::request();

    $input = [
      'username' => $req->data->username ?? '',
      'password' => $req->data->password ?? ''
    ];

    // Validation des entrées
    $res = Validator::validateLogin($input, $repo);

    if (!$res['ok']) {
      $_SESSION['error'] = 'Nom d\'utilisateur ou mot de passe invalide.';
      Flight::redirect('/login');
      return;
    }

    // Recherche de l'utilisateur par username
    $user = $repo->findByUsername($res['values']['username']);

    if (!$user) {
      $_SESSION['error'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
      Flight::redirect('/login');
      return;
    }

    // Vérification du mot de passe
    if (!password_verify($res['values']['password'], $user['password'])) {
      $_SESSION['error'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
      Flight::redirect('/login');
      return;
    }

    // Authentification réussie - Stocker les informations en session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_pdp'] = $user['pdp'] ?? 'default.png';
    $_SESSION['success'] = 'Connexion réussie !';

    // Redirection selon le rôle
    if ($user['role'] === 'admin') {
      Flight::redirect('/admin/users');
    } else {
      Flight::redirect('/objets');
    }
  }

  public static function logout()
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
    
    // Détruire la session
    session_unset();
    session_destroy();
    
    // Recréer une nouvelle session pour le message
    session_start();
    $_SESSION['success'] = 'Vous avez été déconnecté avec succès.';
    
    Flight::redirect('/login');
  }
}
