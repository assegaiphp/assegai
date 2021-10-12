<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Interfaces\IController;
use LifeRaft\Core\Interfaces\IModule;
use ReflectionClass;

class App
{
  private string $path = '/';
  private array $url = [];
  private Request $request;

  public function __construct(
    private array $config = []
  ) {
    $this->request = new Request( app: $this );
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

    $activated_module = $this->getActivatedModule();
    $activated_controller = $this->getActivatedController( module: $activated_module );
    $response = $activated_controller->handleRequest( url: $this->url );

    header('Content-Type: ' . $response->type());
    http_response_code( response_code: $response->status()->code() );
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
    # Get route base
    $endpoint = $this->url()[0];

    # Load routes
    $routes = require_once('app/routes.php');
    $module = isset($routes['/']) ? $routes['/'] : LifeRaft\Modules\Home\HomeModule::class;

    # If route base matches registered route call controller else call Home controller
    if (isset($routes[$endpoint]))
    {
      $module = $routes[$endpoint];
    }

    return new $module();
  }

  /**
   * 
   * Returns a `LifeRaft\Core\IController` that best matches the requested endpoint
   */
  private function getActivatedController(IModule $module): IController
  {
    $activated_controller = $module->rootControllerName();
    
    if (is_null($activated_controller))
    {
      Debugger::log_error('Missing contrller: ' . get_called_class());
      exit(new BadRequestErrorResponse());
    }

    $module->resolveInjectables();

    $dependencies = $module->resolveDependencies(classname: $activated_controller);

    $reflection = new ReflectionClass($activated_controller);

    return $reflection->newInstanceArgs( args: $dependencies );
  }
}
