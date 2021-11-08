<?php

namespace LifeRaft\Lib\Authentication\Strategies;

use LifeRaft\Core\App;
use LifeRaft\Core\Config;
use LifeRaft\Core\Interfaces\IService;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Modules\Users\UsersService;

final class LocalStrategy extends BaseAuthenticationStrategy
{
  public function __construct(
    protected IService $entityService,
    protected ?string $name = '',
    protected ?App $app = null,
    protected ?IService $authenticationService = null,
    protected ?UsersService $usersService = null
  )
  {
  }

  public function authenticate(mixed $data, mixed $params): mixed
  {

  }

  public function validate(string $username, string $password): IEntity
  {
    $entityClassName = Config::get('authentication')['jwt']('entityClassName');
    $entity = new $entityClassName;

    // $this->usersService->find

    return $entity;
  }
}

?>