<?php

namespace LifeRaft\Core\Routing;

use LifeRaft\Core\Interfaces\IModule;
use LifeRaft\Core\Routing\Types\PathMatchingStrategy;

class Route
{
  /**
   * @param string $path 
   * @param null|PathMatchingStrategy $path The path-matching strategy, one of 'prefix' or 'full'.
   * Default is 'prefix'.
   * @param null|IModule $module The module to load when the path matches
   */
  public function __construct(
    protected ?string $path = null,
    protected ?PathMatchingStrategy $pathMatchingStrategy = null,
    protected ?IModule $module = null,
    protected ?string $redirectTo = null,
    protected array $canActivate = [],
  )
  {
    if (is_null($this->pathMatchingStrategy))
    {
      $this->pathMatchingStrategy = PathMatchingStrategy::PREFIX();
    }
  }

  protected function canMatch(): bool
  {
    $canMatch = false;

    // TODO: Implement canMatch logic

    return $canMatch;
  }

  public function canRedirect(): bool
  {
    return !is_null($this->redirectTo);
  }

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

?>