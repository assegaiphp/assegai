<?php

namespace LifeRaft\Core;

/**
 * The **RequestMethod** class enumerates the multiple HTTP request methods.
 */
class RequestMethod
{
  const GET = 'GET';
  const POST = 'POST';
  const PUT = 'PUT';
  const PATCH = 'PATCH';
  const DELETE = 'DELETE';
  const OPTIONS = 'OPTIONS';
  const HEAD = 'HEAD';
}

/**
 * The **Request** class represents the HTTP request and has properties for
 *  the request query string, parameters, HTTP headers, and body
 */
class Request
{
  protected mixed $body;
  protected array $all_headers = [];

  public function __construct(
    public App $app
  ) {
    $this->body = match ($this->method()) {
      RequestMethod::GET      => $_GET,
      RequestMethod::POST     => !empty($_POST) ? $_POST : ( !empty($_FILES) ? $_FILES : file_get_contents('php://input') ),
      RequestMethod::PUT      => file_get_contents('php://input'),
      RequestMethod::PATCH    => file_get_contents('php://input'),
      RequestMethod::DELETE   => !empty($_GET) ? $_GET : file_get_contents('php://input'),
      RequestMethod::HEAD     => NULL,
      RequestMethod::OPTIONS  => NULL,
    };

    if (isset($this->body['path']))
    {
      unset($this->body['path']);
    }

    foreach ($_SERVER as $key => $value)
    {
      if (str_starts_with($key, 'HTTP_'))
      {
        $this->all_headers[$key] = $value;
      }
    }
  }

  public function to_array(): array
  {
    return [
      'app'       => $this->app,
      'body'      => $this->body(),
      'cookies'   => $this->cookies(),
      'fresh'     => $this->fresh(),
      'headers'   => $this->all_headers(),
      'host_name' => $this->host_name(),
      'method'    => $this->method(),
      'remote_ip' => $this->remote_ip(),
      'uri'       => $this->uri(),
      'protocol'  => $this->protocol()
    ];
  }

  public function to_json(): string
  {
    return json_encode($this->to_array());
  }

  public function __toString(): string
  {
    return $this->to_json();
  }

  public function __serialize(): array
  {
    return $this->to_array();
  }

  public function header(string $name): string|null
  {
    $key = strtoupper($name);

    if (isset($_SERVER["HTTP_{$key}"]))
    {
      return $_SERVER["HTTP_{$key}"];
    }

    if (isset($_SERVER[$key]))
    {
      return $_SERVER[$key];
    }

    return null;
  }

  public function all_headers(): array
  {
    return $this->all_headers;
  }

  public function uri(): string
  {
    return $_SERVER['REQUEST_URI']; 
  }

  public function body(): mixed
  {
    return $this->body;
  }

  public function cookies(): string
  {
    return ''; 
  }

  public function fresh(): bool
  {
    return false; 
  }

  public function host_name(): string
  {
    return $_SERVER['HTTP_HOST']; 
  }

  public function method(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function remote_ip(): string
  {
    return $_SERVER['REMOTE_ADDR'];
  }

  public function remote_ips(): string
  {
    return ''; 
  }

  public function protocol(): string
  {
    return $_SERVER['REQUEST_SCHEME'];
  }

  public function query(): string
  {
    return $_SERVER['QUERY_STRING'];
  }
}

?>