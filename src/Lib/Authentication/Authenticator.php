<?php

namespace Assegai\Lib\Authentication;

use Assegai\Core\Config;
use Assegai\Lib\Authentication\Interfaces\IAuthStrategy;
use Assegai\Lib\Authentication\Strategies\JWTStrategy;
use Assegai\Lib\Authentication\Strategies\LocalStrategy;
use Assegai\Lib\Authentication\Strategies\OAuthStrategy;
use ReflectionClass;

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

  /**
   * Returns an instance of an AuthStrategy
   */
  public function getStrategy(string $name, array $args = []): IAuthStrategy|false
  {
    $strategyClass = $this->strategies[$name] ?? false;

    if (is_bool($strategyClass))
    {
      return $strategyClass;
    }
    
    $reflectionClass = new ReflectionClass($strategyClass);

    return $reflectionClass->newInstanceArgs(args: $args);
  }
}

?>