<?php

namespace Assegai\Modules\Authentication;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Result;
use Assegai\Core\BaseService;
use Assegai\Core\Config;
use Assegai\Lib\Authentication\Authenticator;
use Assegai\Lib\Authentication\JWT\JWTHeader;
use Assegai\Lib\Authentication\JWT\JWTPayload;
use Assegai\Lib\Authentication\JWT\JWTToken;
use Assegai\Lib\Authentication\Strategies\JWTStrategy;
use Assegai\Lib\Authentication\Strategies\LocalStrategy;
use Assegai\Lib\Authentication\Strategies\OAuthStrategy;
use Assegai\Modules\Users\UsersService;
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
          $usernameFieldName = Config::get('authentication')['jwt']['entityIdFieldname'];
          $passwordFieldName = Config::get('authentication')['jwt']['entityPasswordFieldname'];
        }
        break;

      default:
    }

    $data = $strategy->validate(username: $entity->$usernameFieldName, password: $entity->$passwordFieldName);

    $isOK = is_bool($data) ? $data : true;

    if ($isOK)
    {
      $token = new JWTToken(
        header: new JWTHeader(),
        payload: new JWTPayload(sub: $data->id),
      );
    }

    return new Result(data: [
      'accessToken' => $token,
      'authentication' => [
        'strategy' => $strategyType,
        'payload' => $token->payload()->toArray()
      ],
      'user' => $data
    ], isOK: $isOK);
  }

  public function validateUser(string $username, string $password): Result
  {
    return new Result();
  }
}
