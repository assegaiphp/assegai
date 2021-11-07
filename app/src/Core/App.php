<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Interfaces\IController;
use LifeRaft\Core\Interfaces\IModule;
use LifeRaft\Core\Responses\BadRequestErrorResponse;
use LifeRaft\Core\Responses\HttpStatus;
use LifeRaft\Core\Responses\Response;
use LifeRaft\Core\Routing\Router;
use LifeRaft\Modules\Home\HomeModule;
use ReflectionClass;

class App
{
  private string $path = '/';
  private array $url = [];

  public function __construct(
    private Request $request,
    private Router $router,
    private array $config = [],
  ) {
    $request->set_app(app: $this);
    if ($this->request->method() === RequestMethod::OPTIONS)
    {
      http_response_code(HttpStatus::OK()->code());
      exit;
    }
  }

  public function request(): Request
  {
    return $this->request;
  }

  /**
   * Gets or sets the application configuration.
   */
  public function config(array|null $config = null): array
  {
    if (!empty($config))
    {
      $this->config = $config;
    }

    return $this->config = !is_null($config) ? $config : [];
  }

  public function run(): void
  {
    $this->parseURL();

    $activatedModule      = $this->getActivatedModule();
    $activatedController  = $this->getActivatedController( module: $activatedModule );
    $response             = $activatedController->handleRequest( url: $this->url );

    $this->respond(response: $response);  
  }

  public function respond(Response $response): void
  {
    exit($response);
  }

  private function parseURL(): void
  {
    if (isset($_GET['path']) && !empty($_GET['path']))
    {
      $this->path = $_GET['path'];
    }
    else
    {
      $this->path = '/';
    }

    $this->url = explode('/', $this->path);

    if (empty($this->url) || $this->url[0] == 'index.php')
    {
      $this->url = ['home'];
    }
  }

  /**
   * Returns the requested path.
   * 
   * @return string Returns the requested path.
   */
  public function path(): string
  {
    return $this->path;
  }
  /**
   * Returns the requested url.
   * 
   * @return string Returns the requested url.
   */
  public function url(): array
  {
    return $this->url;
  }

  private function getActivatedModule(): IModule
  {
    # Load routes
    $routes = require_once('app/routes.php');

    if (!is_array($routes))
    {
      exit(new BadRequestErrorResponse(message: 'Invalid route'));
    }

    $this->router->setRoutes(routes: $routes);

    $this->router->route();
    $activatedRoute = $this->router->activatedRoute();

    if (is_null($activatedRoute))
    {
      return new HomeModule();
    }

    return $activatedRoute->module();
  }

  /**
   * 
   * Returns a `LifeRaft\Core\IController` that best matches the requested endpoint
   */
  private function getActivatedController(IModule $module): IController
  {
    $activatedController = $module->rootControllerName();
    
    if (is_null($activatedController))
    {
      Debugger::log_error('Missing contrller: ' . get_called_class());
      exit(new BadRequestErrorResponse());
    }

    $module->resolveInjectables();

    $dependencies = $module->getDependencies(classname: $activatedController);

    $reflection = new ReflectionClass($activatedController);

    return $reflection->newInstanceArgs( args: $dependencies );
  }
}
