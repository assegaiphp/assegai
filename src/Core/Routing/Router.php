<?php

namespace Assegai\Core\Routing;

use Assegai\Core\Interfaces\IContainer;
use Psr\Container\ContainerInterface;

final class Router
{
  private ?Route $activatedRoute = null;

  public function __construct(
    private ?array $routes = null,
    public readonly ContainerInterface|IContainer $injector
  ) { }

  public function activatedRoute(): ?Route
  {
    return $this->activatedRoute;
  }

  public function setRoutes(array $routes)
  {
    $this->routes = $routes;
  }

  public function route(): void
  {
    foreach ($this->routes as $route)
    {
      if (method_exists($route, 'isActive') && $route->isActive())
      {
        $this->setActivatedRoute(route: $route);
        break;
      }
    }
  }

  private function setActivatedRoute(Route $route): void
  {
    $this->activatedRoute = $route;
  }
}

