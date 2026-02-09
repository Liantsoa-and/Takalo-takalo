<?php
class Validator
{

  public static function validateLogin(array $input, ?UserRepository $repo = null)
  {
    $errors = [
      'email' => ''
    ];

    $values = [
      'email' => trim((string) ($input['email'] ?? ''))
    ];

    if ($values['email'] === '') {
      $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "L'email n'est pas valide (ex: nom@domaine.com).";
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
