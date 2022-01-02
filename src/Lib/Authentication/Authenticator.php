<?php

namespace Assegai\Lib\Authentication;

use Assegai\Core\Config;
use Assegai\Lib\Authentication\Interfaces\IAuthStrategy;
use Assegai\Lib\Authentication\Strategies\JWTStrategy;
use Assegai\Lib\Authentication\Strategies\LocalStrategy;
use Assegai\Lib\Authentication\Strategies\OAuthStrategy;

/**
 * The `Authenticator` class houses methods for granging and managing user 
 * authentication.
 */
final class Authenticator
{
  private array $strategies = [
    'local' => LocalStrategy::class,
    'jwt' => JWTStrategy::class,
    'oauth' => OAuthStrategy::class
  ];

  public function __construct(
    private ?IAuthStrategy $strategy = null
  )
  {
    $strategies = Config::get('authentication')['strategies'] ?? [];
    $this->strategies = array_merge($this->strategies, $strategies);
  }

  public function getStrategy(string $name): IAuthStrategy|false
  {
    return $this->strategies[$name] ?? false;
  }
}

?>