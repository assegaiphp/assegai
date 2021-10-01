<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Get;
use LifeRaft\Core\Attributes\POST;
use LifeRaft\Core\Attributes\PUT;
use LifeRaft\Core\Attributes\PATCH;
use LifeRaft\Core\Attributes\DELETE;
use LifeRaft\Core\Attributes\OPTIONS;
use ReflectionClass;
use LifeRaft\Core\Handler;

/**
 * Controllers are responsible for handling incoming requests and returning responses to the client.
 */
abstract class Controller
{
  protected string $prefix = '';
  protected Handler $handler;

  public function __construct(
    protected Request $request
  ) {
    if (!empty($request->uri()))
    {
      $exploded_url = explode('/', trim($request->uri(), '/'));
      $this->prefix = array_shift($exploded_url);
    }
  }

  public function handle_request(array $url): Response
  {
    try {
      $handler = $this->get_activated_handler();

      # Check if handler is defined
      if (empty($handler))
      {
        return new NotFoundErrorResponse();
      }
      # Else respond with Unknow error

      return call_user_func_array([$this, $handler->method()->name], $handler->attribute()->args);
    }
    catch (\Exception $e)
    {
      throw new \Exception('RequestHandlingException: ' . $e->getMessage());
    }
  }

  protected function get_activated_handler(): Handler|null
  {
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

    # Check if we have handlers for request_method
    if (empty($methods))
    {
      
    }

    foreach ($methods as $method)
    {
      $attributes = $method->getAttributes($activated_attribute_class);

      if (!empty($attributes))
      {
        foreach ($attributes as $attribute)
        {
          $instance = $attribute->newInstance();

          $path = '/' . $this->prefix . '/' . $instance->path;
          $path = str_replace('//', '/', $path);
          $pattern = preg_replace('/(:[\w]+)/', '.+', $path);
          $pattern = "(^$pattern$)";
          
          $can_activate = preg_match( pattern: $pattern, subject: $this->request->uri() );
          
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

    return $handler;
  }

  protected function is_match(string $pattern, string $path): bool
  {
    return false;
  }

  /**
   * 
   */
  protected function respond(Response $response): void
  {
    http_response_code( response_code: $response->status()->code());
    echo $response;
    exit;
  }
}
