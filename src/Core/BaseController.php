<?php

namespace Assegai\Core;

use Assegai\Core\Attributes\Controller;
use Assegai\Core\Interfaces\IController;
use Assegai\Core\Attributes\GET;
use Assegai\Core\Attributes\POST;
use Assegai\Core\Attributes\PUT;
use Assegai\Core\Attributes\PATCH;
use Assegai\Core\Attributes\DELETE;
use Assegai\Core\Attributes\OPTIONS;
use Assegai\Core\Responses\HttpStatusCode;
use Assegai\Core\Responses\MethodNotAllowedErrorResponse;
use Assegai\Core\Responses\NotFoundErrorResponse;
use Assegai\Core\Responses\NotImplementedErrorResponse;
use Assegai\Core\Responses\Response;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;
use ReflectionMethod;

/**
 * Controllers are responsible for handling incoming requests and returning 
 * responses to the client.
 */
class BaseController implements IController
{
  protected string $prefix = '';
  protected string $path = '/';
  protected ?string $host = null;
  protected ?HttpStatusCode $status = null;
  protected Handler $handler;
  protected array $forbiddenMethods = [];

  public function __construct(
    protected Request $request
  ) {
    $reflection = new ReflectionClass(objectOrClass: $this);
    $attributes = $reflection->getAttributes(Controller::class);
    
    foreach ($attributes as $attribute)
    {
      $instance = $attribute->newInstance();
      $this->path = $instance->path;
      $this->host = $instance->host;
      $this->status = $instance->status;
      $this->forbiddenMethods = $instance->forbiddenMethods;
    }

    if (!empty($request->uri()))
    {
      $explodedURI = explode('/', trim($request->uri(), '/'));
      $this->prefix = array_shift($explodedURI);
    }
  }

  public function path(): string { return $this->path; }

  public function host(): string { return $this->host; }

  public function status(): string { return $this->status; }

  public function handleRequest(array $url): Response
  {
    try
    {
      $handler = $this->getActivatedHandler();

      # Check if handler is defined
      if (empty($handler))
      {
        # Else respond with Unknown error
        return new NotFoundErrorResponse();
      }

      $methodParams = [];
      $methodReflection = new ReflectionMethod(objectOrMethod: $this, method: $handler->method()->name);
      $parametersReflection = $methodReflection->getParameters();

      foreach ($parametersReflection as $param)
      {
        if ( isset($handler->attribute()->args[$param->getName()]) )
        {
          $methodParams[$param->getName()] = $handler->attribute()->args[$param->getName()];
        }
      }

      return call_user_func_array([$this, $handler->method()->name], $methodParams);
    }
    catch (Exception $e)
    {
      exit('RequestHandlingException: ' . $e->getMessage());
    }
  }

  protected function getActivatedHandler(): Handler|null
  {
    global $app;

    if (!isset($this->request))
    {
      $this->request = $app->request();
    }

    # Check if forbidden method
    if (in_array($this->request->method(), $this->forbiddenMethods))
    {
      $this->respond(new MethodNotAllowedErrorResponse());
    }

    $activatedAttributeClass = match ($this->request->method()) {
      RequestMethod::POST     => POST::class,
      RequestMethod::PUT      => PUT::class,
      RequestMethod::PATCH    => PATCH::class,
      RequestMethod::DELETE   => DELETE::class,
      RequestMethod::OPTIONS  => OPTIONS::class,
      default                 => GET::class
    };

    $reflection = new ReflectionClass( objectOrClass: $this );
    $methods = $reflection->getMethods();
    $handler = NULL;

    $attributesFound = false;

    foreach ($methods as $method)
    {
      $attributes = $method->getAttributes($activatedAttributeClass);

      if (!empty($attributes))
      {
        $attributesFound = true;

        foreach ($attributes as $attribute)
        {
          $attributeInstance = $attribute->newInstance();

          $path = '/' . $this->path . '/' . $attributeInstance->path;
          $path = str_replace('///', '/', $path);
          $path = str_replace('//', '/', $path);
          if ($path === '/')
          {
            $path = '.+';
          }

          $pattern = preg_replace('/(:[\w]+)/', '.+', $path);
          $pattern = "(^$pattern$)";
          $subject = str_ends_with($this->request->path(), '/') ? $this->request->path() : $this->request->path() . '/';

          $canActivate = preg_match( pattern: $pattern, subject: '/' . $subject );

          if ($canActivate && $attributeInstance->canActivate)
          {
            $handler = new Handler( method: $method, attribute: $attributeInstance);
            break;
          }
        }
      }
      
      if (!empty($handler))
      {
        break;
      }
    }

    # Check if we have handlers for request_method
    if (!$attributesFound)
    {
      $this->respond(new NotImplementedErrorResponse());
    }

    return $handler;
  }

  protected function isMatch(string $pattern, string $path): bool
  {
    return false;
  }

  #[NoReturn]
  public function respond(Response $response): void
  {
    exit($response);
  }
}
