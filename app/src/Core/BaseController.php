<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Interfaces\IController;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\POST;
use LifeRaft\Core\Attributes\PUT;
use LifeRaft\Core\Attributes\PATCH;
use LifeRaft\Core\Attributes\DELETE;
use LifeRaft\Core\Attributes\OPTIONS;
use LifeRaft\Core\Responses\MethodNotAllowedErrorResponse;
use LifeRaft\Core\Responses\NotFoundErrorResponse;
use LifeRaft\Core\Responses\NotImplementedErrorResponse;
use LifeRaft\Core\Responses\Response;
use ReflectionClass;

/**
 * Controllers are responsible for handling incoming requests and returning 
 * responses to the client.
 */
class BaseController implements IController
{
  protected string $prefix = '';
  protected Handler $handler;
  protected array $forbiddenMethods = [];

  public function __construct(
    protected Request $request
  ) {
    if (!empty($request->uri()))
    {
      $explodedURI = explode('/', trim($request->uri(), '/'));
      $this->prefix = array_shift($explodedURI);
    }
  }

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

      return call_user_func_array([$this, $handler->method()->name], $handler->attribute()->args);
    }
    catch (\Exception $e)
    {
      throw new \Exception('RequestHandlingException: ' . $e->getMessage());
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
      RequestMethod::GET      => Get::class,
      RequestMethod::POST     => POST::class,
      RequestMethod::PUT      => PUT::class,
      RequestMethod::PATCH    => PATCH::class,
      RequestMethod::DELETE   => DELETE::class,
      RequestMethod::OPTIONS  => OPTIONS::class,
      default                 => Get::class
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
          $instance = $attribute->newInstance();

          $path = '/' . $this->prefix . '/' . $instance->path;
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

          if ($canActivate)
          {
            $handler = new Handler( method: $method, attribute: $instance);
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

  public function respond(Response $response): void
  {
    http_response_code( response_code: $response->status()->code());
    exit($response);
  }
}
