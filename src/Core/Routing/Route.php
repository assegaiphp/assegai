<?php

namespace Assegai\Core\Routing;

use Assegai\Core\Interfaces\IModule;
use Assegai\Core\Routing\Types\PathMatchingStrategy;
use JetBrains\PhpStorm\Pure;

class Route
{
  /**
   * @param string|null $path The path to match.
   * @param null|PathMatchingStrategy $pathMatchingStrategy The path-matching strategy, one of 'prefix' or 'full'.
   * Default is 'prefix'.
   * @param IModule|string|null $module The module to load when the path matches.
   * @param string|null $redirectTo
   * @param \Assegai\Core\Interfaces\ICanActivate[] $canActivate A list of Guards to check
   * @param Route[] $children A list of child routes
   */
  public function __construct(
    protected ?string $path = null,
    protected ?PathMatchingStrategy $pathMatchingStrategy = null,
    protected null|IModule|string $module = null,
    protected ?string $redirectTo = null,
    protected array $canActivate = [],
    protected array $children = []
  )
  {
    if (is_null($this->pathMatchingStrategy))
    {
      $this->pathMatchingStrategy = PathMatchingStrategy::PREFIX();
    }

    if (is_string($this->module))
    {
      $this->module = new $this->module;
    }
  }

  public function module(): IModule
  {
    if (is_string($this->module))
    {
      $this->module = new $this->module;
    }

    return $this->module;
  }

  #[Pure]
  protected function canMatch(): bool
  {
    $canMatch = false;
    $path = '/' . ($_GET['path'] ?? '');

    return match (strval($this->pathMatchingStrategy)) {
      strval(PathMatchingStrategy::FULL()) => $path === '/' . $this->path,
      default => str_starts_with(haystack: $path, needle: '/' . $this->path),
    };
  }

  public function canRedirect(): bool
  {
    return !is_null($this->redirectTo);
  }

  /**
   * Returns `true` if the current route is activateable. A
   */
  protected function canActivate(): bool
  {
    $canActivate = true;

    foreach ($this->canActivate as $guard)
    {
      if (!$guard->canActivate())
      {
        $canActivate = false;
      }
    }

    return $canActivate;
  } 

  public function isActive(): bool
  {
    return $this->canMatch() && $this->canActivate();
  }
}

