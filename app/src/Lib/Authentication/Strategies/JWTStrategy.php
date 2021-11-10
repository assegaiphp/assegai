<?php

namespace Assegai\Lib\Authentication\Strategies;

use Assegai\Core\App;
use Assegai\Core\Config;
use Assegai\Core\Interfaces\IService;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Modules\Users\UsersService;

final class JWTStrategy extends BaseAuthenticationStrategy
{
  public function __construct(
    protected UsersService $usersService,
    protected ?string $name = '',
    protected ?App $app = null,
    protected ?IService $authenticationService = null,
  )
  {
    parent::__construct(
      name: $name,
      app: $app,
      authenticationService: $this->authenticationService,
      entityService: $this->usersService
    );
  }

  public function authenticate(mixed $data, mixed $params): mixed
  {

  }

  public function validate(string $username, string $password): IEntity
  {
    $entityClassName = Config::get('authentication')['jwt']['entityClassName'];
    $entity = new $entityClassName;

    // $this->usersService->find

    return $entity;
  }
}

?>