<?php

namespace LifeRaft\Modules\Authentication;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\Result;
use LifeRaft\Core\BaseService;
use LifeRaft\Core\Config;
use LifeRaft\Lib\Authentication\Authenticator;
use LifeRaft\Lib\Authentication\Strategies\JWTStrategy;
use LifeRaft\Lib\Authentication\Strategies\LocalStrategy;
use LifeRaft\Lib\Authentication\Strategies\OAuthStrategy;
use LifeRaft\Modules\Users\UsersService;
use stdClass;

#[Injectable]
class AuthenticationService extends BaseService
{
  public function __construct(
    protected UsersService $usersService
  )
  {
    parent::__construct();
  }

  public function findAll(): Result
  {
    return new Result();
  }

  public function find(int $id): Result
  {
    return new Result();
  }

  public function create(mixed $entity): Result
  {
    $strategy = Config::get('authentication')['default_strategy'];
    $credentials = $entity;

    if ($credentials instanceof stdClass && isset($credentials->strategy))
    {
      $strategy = $credentials->strategy;
    }
    else if (is_array(value: $credentials) && isset($credentials['strategy']))
    {
      $strategy = $credentials['strategy'];
    }

    $strategyType = $strategy;

    $strategy = match($strategyType) {
      'local' => new LocalStrategy( usersService: $this->usersService ),
      'jwt'   => new JWTStrategy( usersService: $this->usersService ),
      'oauth' => new OAuthStrategy(),
      default => new LocalStrategy( usersService: $this->usersService )
    };

    $authenticator = new Authenticator(strategy: $strategy);
    
    $usernameFieldName = 'username';
    $passwordFieldName = 'password';

    switch($strategyType)
    {
      case 'local':
        if (isset(Config::get('authentication')['jwt']))
        {

        }
        break;

      default:
    }

    $data = $strategy->validate(username: $entity->$usernameFieldName, password: $entity->$passwordFieldName);

    $isOK = is_bool($data) ? $data : true;

    return new Result(data: [$data], isOK: $isOK);
  }

  public function update(): Result
  {
    return new Result();
  }

  public function patch(): Result
  {
    return new Result();
  }

  public function delete(): Result
  {
    return new Result();
  }

  public function validateUser(string $username, string $password): Result
  {
    return new Result();
  }
}
