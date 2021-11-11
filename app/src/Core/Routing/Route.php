<?php

namespace Assegai\Core\Routing;

use Assegai\Core\Interfaces\IModule;
use Assegai\Core\Routing\Types\PathMatchingStrategy;

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
    protected null|IModule|string $module = null,
    protected ?string $redirectTo = null,
    protected array $canActivate = [],
  )
  {
    if (is_null($this->pathMatchingStrategy))
    {
      $this->pathMatchingStrategy = PathMatchingStrategy::PREFIX();
    }

    if (!is_null($this->module) && is_string($this->module))
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

  protected function canMatch(): bool
  {
    $canMatch = false;
    $path = '/' . (isset($_GET['path']) ? $_GET['path'] : '');

    switch(strval($this->pathMatchingStrategy))
    {
      case strval(PathMatchingStrategy::FULL()):
        $canMatch = $path === '/' . $this->path;
        break;

      case strval(PathMatchingStrategy::PREFIX()):
      default:
        $canMatch = str_starts_with(haystack: $path, needle: '/' . $this->path);
    }

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