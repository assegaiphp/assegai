<?php

namespace Assegai\Lib\Authentication\Strategies;

use Assegai\Core\App;
use Assegai\Core\Config;
use Assegai\Core\Interfaces\ICRUDService;
use Assegai\Core\Interfaces\IService;
use Assegai\Core\Responses\NotFoundErrorResponse;
use Assegai\Core\Responses\NotImplementedErrorResponse;
use Assegai\Core\Responses\UnauthorizedErrorResponse;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Queries\FindOptions;

final class JWTStrategy extends BaseAuthenticationStrategy
{
  public function __construct(
    protected ICRUDService $usersService,
    protected ?string $name = 'jwt',
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
    exit(new NotImplementedErrorResponse(message: 'Not implemented: JWTStrategy::authenticate() on line ' . __LINE__));
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
      : 'password';

    $result =
      $this->usersService->findOne(
        conditions: "`$usernameField`='$username'",
        options: new FindOptions(exclude: [])
      );

    if ($result->isOK())
    {
      $errorMessage = "Incorrect $usernameField and/or $passwordField. Please try again.";
      if (empty($result->value()))
      {
        exit(new NotFoundErrorResponse(message: $errorMessage));
      }

      $entity = $entity::newInstanceFromObject(object: $result->value()[0]);

      if (!password_verify($password, $entity->$passwordField))
      {
        exit(new UnauthorizedErrorResponse(message: $errorMessage));
      }

      return $entity;
    }

    return false;
  }
}

?>