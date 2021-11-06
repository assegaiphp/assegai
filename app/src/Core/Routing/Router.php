<?php

namespace LifeRaft\Core\Routing;

final class Router
{
  private ?Route $activatedRoute = null;

  public function __construct(
    private ?array $routes = null
  ) { }

  public function activatedRoute(): Route
  {
    return $this->activatedRoute;
  }

  public function setRoutes(array $routes)
  {
    $this->routes = $routes;
  }

  public function route(): Route
  {
    foreach ($this->routes as $route)
    {
      if (method_exists($route, 'isActive') && $route->isActive())
      {
        $this->setActivatedRoute(route: $route);
        break;
      }
    }

    return $this->activatedRoute();
  }

  private function setActivatedRoute(Route $route): void
  {
    $this->activatedRoute = $route;
  }
}

?>