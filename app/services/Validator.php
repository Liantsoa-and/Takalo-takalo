<?php
class Validator
{

  public static function validateLogin(array $input, ?UserRepository $repo = null)
  {
    $errors = [
      'username' => '',
      'password' => ''
    ];

    $values = [
      'username' => trim((string) ($input['username'] ?? '')),
      'password' => trim((string) ($input['password'] ?? ''))
    ];

    // Validation username
    if ($values['username'] === '') {
      $errors['username'] = "Le nom d'utilisateur est obligatoire.";
    } elseif (strlen($values['username']) < 3) {
      $errors['username'] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    }

    // Validation password
    if ($values['password'] === '') {
      $errors['password'] = "Le mot de passe est obligatoire.";
    } elseif (strlen($values['password']) < 4) {
      $errors['password'] = "Le mot de passe doit contenir au moins 4 caractères.";
    }

    $ok = true;
    foreach ($errors as $m) {
      if ($m !== '') {
        $ok = false;
        break;
      }
    }

    return ['ok' => $ok, 'errors' => $errors, 'values' => $values];
  }
}
