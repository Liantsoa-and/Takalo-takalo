<?php
class AuthController
{

  private static function ensureSession()
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  // Verifie que l'utilisateur est connecte 
  public static function requireLogin(?string $role = null)
  {
    self::ensureSession();

    if (!isset($_SESSION['user_id'])) {
      $_SESSION['error'] = 'Veuillez vous connecter pour accéder à cette page.';
      Flight::redirect('/login');
      exit;
    }

    if ($role !== null && ($_SESSION['user_role'] ?? '') !== $role) {
      $_SESSION['error'] = 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.';
      Flight::redirect('/objets');
      exit;
    }
  }

  public static function showLogin()
  {
    self::ensureSession();
    
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
    self::ensureSession();

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
    if ($res['values']['password'] !== $user['password']) {
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

  public static function showRegister()
  {
    self::ensureSession();

    if (isset($_SESSION['user_id'])) {
      Flight::redirect('/objets');
      return;
    }

    Flight::render('register');
  }

  public static function postRegister()
  {
    self::ensureSession();

    $pdo = Flight::db();
    $repo = new UserRepository($pdo);
    $req = Flight::request();

    $input = [
      'username' => $req->data->username ?? '',
      'password' => $req->data->password ?? '',
      'password_confirm' => $req->data->password_confirm ?? ''
    ];

    $res = Validator::validateRegister($input, $repo);

    if (!$res['ok']) {
      $_SESSION['errors'] = array_filter($res['errors'], function($e) { return $e !== ''; });
      $_SESSION['old'] = ['username' => $input['username']];
      Flight::redirect('/register');
      return;
    }

    // Upload photo de profil
    $pdpFilename = 'default.png';
    if (!empty($_FILES['pdp']) && $_FILES['pdp']['error'] === UPLOAD_ERR_OK) {
      $tmp = $_FILES['pdp']['tmp_name'];
      $orig = $_FILES['pdp']['name'];
      $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      $allowed = ['jpg','jpeg','png','gif','webp'];
      if (in_array($ext, $allowed)) {
        $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destDir = realpath(__DIR__ . '/../../public/assets/images/pdp');
        if ($destDir && move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $name)) {
          $pdpFilename = $name;
        }
      }
    }

    // Créer l'utilisateur
    $id = $repo->createFull([
      'username' => $res['values']['username'],
      'password' => $res['values']['password'],
      'role' => 'user',
      'pdp' => $pdpFilename
    ]);

    if ($id) {
      $_SESSION['success'] = 'Compte créé avec succès ! Connectez-vous.';
      Flight::redirect('/login');
    } else {
      $_SESSION['error'] = 'Erreur lors de la création du compte.';
      $_SESSION['old'] = ['username' => $input['username']];
      Flight::redirect('/register');
    }
  }

  public static function logout()
  {
    self::ensureSession();
    
    // Détruire la session
    session_unset();
    session_destroy();
    
    // Recréer une nouvelle session pour le message
    session_start();
    $_SESSION['success'] = 'Vous avez été déconnecté avec succès.';
    
    Flight::redirect('/login');
  }


}
