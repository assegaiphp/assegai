<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Interfaces\IController;
use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\POST;
use LifeRaft\Core\Attributes\PUT;
use LifeRaft\Core\Attributes\PATCH;
use LifeRaft\Core\Attributes\DELETE;
use LifeRaft\Core\Attributes\OPTIONS;
use ReflectionClass;

/**
 * Controllers are responsible for handling incoming requests and returning 
 * responses to the client.
 */
class BaseController implements IController
{
  protected string $prefix = '';
  protected Handler $handler;
  protected array $forbidden_methods = [];

  public function __construct(
    protected Request $request
  ) {
    if (!empty($request->uri()))
    {
      $exploded_url = explode('/', trim($request->uri(), '/'));
      $this->prefix = array_shift($exploded_url);
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
        return new NotFoundErrorResponse();
      }
      # Else respond with Unknown error

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
    if (in_array($this->request->method(), $this->forbidden_methods))
    {
      $this->respond(new MethodNotAllowedErrorResponse());
    }

    $activated_attribute_class = match ($this->request->method()) {
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

    $attributes_found = false;

    foreach ($methods as $method)
    {
      $attributes = $method->getAttributes($activated_attribute_class);

      if (!empty($attributes))
      {
        $attributes_found = true;

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
          $subject = str_ends_with($this->request->uri(), '/') ? $this->request->uri() : $this->request->uri() . '/';

          $can_activate = preg_match( pattern: $pattern, subject: $subject );

          if ($can_activate)
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
    if (!$attributes_found)
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
