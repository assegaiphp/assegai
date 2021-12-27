<?php

namespace Assegai\Core;

use JetBrains\PhpStorm\Pure;

/**
 * The **Request** class represents the HTTP request and has properties for 
 * the request query string, parameters, HTTP headers, and body
 */
class Request
{
  protected mixed $body = null;
  protected array $allHeaders = [];
  protected ?App $app = null;

  protected static Request $instance;

  public function __construct() {
    $this->body = match ($this->method()) {
      RequestMethod::GET      => $_GET,
      RequestMethod::POST     => !empty($_POST) ? $_POST : ( !empty($_FILES) ? $_FILES : file_get_contents('php://input') ),
      RequestMethod::PUT,
      RequestMethod::PATCH    => file_get_contents('php://input'),
      RequestMethod::DELETE   => !empty($_GET) ? $_GET : file_get_contents('php://input'),
      RequestMethod::HEAD,
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
        $this->allHeaders[$key] = $value;
      }
    }

    if (isset(Request::$instance) || empty(Request::$instance))
    {
      Request::$instance = $this;
    }
  }

  public static function instance(): Request
  {
    return Request::$instance;
  }

  public function app(): App
  {
    return $this->app;
  }

  public function set_app(App $app): void
  {
    $this->app = $app;
  }

  #[Pure]
  public function toArray(): array
  {
    return [
      'app'       => $this->app,
      'body'      => $this->body(),
      'cookies'   => $this->cookies(),
      'fresh'     => $this->fresh(),
      'headers'   => $this->allHeaders(),
      'host_name' => $this->host_name(),
      'method'    => $this->method(),
      'remote_ip' => $this->remote_ip(),
      'uri'       => $this->uri(),
      'protocol'  => $this->protocol()
    ];
  }

  public function toJSON(): string
  {
    return json_encode($this->toArray());
  }

  public function __toString(): string
  {
    return $this->toJSON();
  }

  #[Pure]
  public function __serialize(): array
  {
    return $this->toArray();
  }

  public function header(string $name): string
  {
    $key = strtoupper($name);

    if (isset($_SERVER["HTTP_$key"]))
    {
      return $_SERVER["HTTP_$key"];
    }

    if (isset($_SERVER[$key]))
    {
      return $_SERVER[$key];
    }

    return '';
  }

  public function allHeaders(): array
  {
    return $this->allHeaders;
  }

  public function uri(): string
  {
    return $_SERVER['REQUEST_URI'] ?? '/';
  }

  public function path(): string
  {
    return $_GET['path'] ?? '/';
  }

  public function limit(): int
  {
    return $_GET['limit'] ?? Config::get('request')['DEFAULT_LIMIT'];
  }

  public function skip(): int
  {
    return $_GET['skip'] ?? Config::get('request')['DEFAULT_SKIP'];
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
    return $_SERVER['HTTP_HOST'] ?? 'localhost';
  }

  public function method(): string
  {
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
  }

  public function remote_ip(): string
  {
    return $_SERVER['REMOTE_ADDR'] ?? '::1';
  }

  public function remote_ips(): string
  {
    return ''; 
  }

  public function protocol(): string
  {
    return $_SERVER['REQUEST_SCHEME'] ?? 'http';
  }

  public function query(): string
  {
    return $_SERVER['QUERY_STRING'] ?? '';
  }
}
