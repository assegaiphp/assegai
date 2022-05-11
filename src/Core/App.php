<?php

namespace Assegai\Core;

use Assegai\Core\Interfaces\ICanActivate;
use Assegai\Core\Interfaces\IController;
use Assegai\Core\Interfaces\IModule;
use Assegai\Core\Responses\BadRequestErrorResponse;
use Assegai\Core\Responses\HttpStatus;
use Assegai\Core\Responses\Response;
use Assegai\Core\Routing\Router;
use AssegaiPHP\Modules\Home\HomeModule;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;
use ReflectionException;

class App
{
  private string $path = '/';
  private array $url = [];

  public function __construct(
    private Request $request,
    private Router $router,
    private array $config = [],
  ) {
    $request->setApp(app: $this);
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

  public function run(): never
  {
    $this->parseURL();

    $activatedModule      = $this->getActivatedModule();
    $activatedController  = $this->getActivatedController( module: $activatedModule );
    $activatedController->init();
    $response             = $activatedController->handleRequest( url: $this->url );

    $this->respond(response: $response);  
  }

  public function respond(Response $response): never
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
   * Returns the requested url as a list of tokens.
   * 
   * @return array Returns the requested url as list of tokens.
   */
  public function url(): array
  {
    return $this->url;
  }

  /**
   * 
   */
  public function useGlobalGuards(array $guards): void
  {
    $GLOBALS['guards'] = [];
    foreach ($guards as $guard)
    {
      if ($guard instanceof ICanActivate)
      {
        $GLOBALS['guards'][] = $guard;
      }
    }
  }

  public function getGlobalGuards(): array
  {
    return $GLOBALS['guards'];
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
   * Returns a `Assegai\Core\IController` that best matches the requested endpoint.
   *
   * @param IModule $module The module where the controller is declared.
   * @return IController Returns the activated controller.
   */
  private function getActivatedController(IModule $module): IController
  {
    $activatedController = $module->rootControllerName();

    if (is_null($activatedController))
    {
      Debugger::logError('Missing controller: ' . get_called_class());
      exit(new BadRequestErrorResponse());
    }

    $module->resolveInjectables();

    $dependencies = $module->getDependencies(classname: $activatedController);

    try
    {
      return $this->router->injector->get($activatedController);
    }
    catch (Exception $exception)
    {
      exit($exception->getMessage());
    }
  }
}
