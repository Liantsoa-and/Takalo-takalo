<?php
class UserService
{
  private $repo;
  public function __construct(UserRepository $repo)
  {
    $this->repo = $repo;
  }

  public function loginOrRegister(string $email)
  {
    $user = $this->repo->findByEmail($email);
    if ($user) {
      // L'utilisateur existe déjà, on le retourne
      return $user;
    }
    // L'utilisateur n'existe pas, on le crée et on le retourne
    $userId = $this->repo->create($email);
    return $this->repo->findByEmail($email);
  }

}
