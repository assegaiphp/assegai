<?php

namespace Assegai\Lib\Authentication\Strategies;

use Assegai\Core\App;
use Assegai\Core\Config;
use Assegai\Core\Interfaces\IService;
use Assegai\Core\Responses\NotFoundErrorResponse;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Modules\Users\UsersService;

final class LocalStrategy extends BaseAuthenticationStrategy
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

  public function validate(string $username, string $password): IEntity|false
  {
    $entityClassName = Config::get('authentication')['jwt']['entityClassName'];
    $entity = new $entityClassName;

    $usernameField = isset(Config::get('authentication')['jwt']['entityIdFieldname'])
      ? Config::get('authentication')['jwt']['entityIdFieldname']
      : 'username';
    $passwordField = isset(Config::get('authentication')['jwt']['entityPasswordFieldname'])
      ? Config::get('authentication')['jwt']['entityPasswordFieldname']
      : 'username';

    $result = $this->usersService->findOne(conditions: "`$usernameField`='$username'");

    if ($result->isOK())
    {
      if (empty($result->value()))
      {
        exit(new NotFoundErrorResponse(message: "Incorrect $usernameField and/or $passwordField. Please try again."));
      }

      return $entity::newInstanceFromObject(object: $result->value()[0]);
    }

    return false;
  }
}

?>