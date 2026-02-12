<?php
class AuthController
{

  public static function showLogin()
  {
    Flight::render('login');
  }

  public static function postLogin()
  {

    $pdo = Flight::db();
    $repo = new UserRepository($pdo);
    $svc = new UserService($repo);

    $req = Flight::request();

    $input = [
      'email' => $req->data->email
    ];
    echo $input['email'];

    $res = Validator::validateLogin($input, $repo);

    if ($res['ok']) {
      $user = $svc->loginOrRegister((string) $input['email']);
      if ($user) {
        if (session_status() !== PHP_SESSION_ACTIVE)
          session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        Flight::redirect('/messages');
        return;
      }

      // Erreur inattendue
      $res['errors']['_global'] = 'Erreur lors de la connexion.';
    }

    Flight::redirect('/');
  }

  public static function logout()
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_destroy();
    }
    Flight::redirect('/login');
  }

}
