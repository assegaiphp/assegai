<?php

namespace Assegai\Core;

use Assegai\Lib\Authentication\JWT\JWTToken;
use JetBrains\PhpStorm\Pure;
use stdClass;

/**
 * The **Request** class represents the HTTP request and has properties for 
 * the request query string, parameters, HTTP headers, and body
 */
class Request
{
  protected mixed $body = null;
  protected array $allHeaders = [];
  protected ?App $app = null;

  protected static ?Request $instance = null;

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
    if (!Request::$instance)
    {
      Request::$instance = new Request;
    }
    return Request::$instance;
  }

  public function app(): App
  {
    return $this->app;
  }

  public function setApp(App $app): void
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
      'host_name' => $this->hostName(),
      'method'    => $this->method(),
      'remote_ip' => $this->remoteIp(),
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
    return $_GET['limit'] ?? Config::get('request')['DEFAULT_LIMIT'] ?? 10;
  }

  public function skip(): int
  {
    return $_GET['skip'] ?? Config::get('request')['DEFAULT_SKIP'] ?? 0;
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

  public function hostName(): string
  {
    return $_SERVER['HTTP_HOST'] ?? 'localhost';
  }

  public function method(): string
  {
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
  }

  public function remoteIp(): string
  {
    return $_SERVER['REMOTE_ADDR'] ?? '::1';
  }

  public function remoteIps(): string
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

  /**
   * Returns the access token provided with the request.
   * 
   * @param bool $deconstruct If set to TRUE, then an object will be return on success.
   * 
   * @return null|string|stdClass|false Returns the access token provided with the 
   * request, null if non was set and false if the supplied token is invalid.
   */
  public function accessToken(bool $deconstruct = false): null|string|stdClass|false
  {
    $accessToken = $this->header('Authorization');

    if (empty($accessToken))
    {
      return NULL;
    }

    if (!str_contains($accessToken, 'BEARER'))
    {
      return NULL;
    }

    $matches = [];
    if (!preg_match('/Bearer (.*)/', $accessToken, $matches))
    {
      return false;
    }

    if (count($matches) < 2)
    {
      return false;
    }

    $tokenString = $matches[1];

    if ($deconstruct)
    {
      return (object) JWTToken::parse(tokenString: $tokenString, returnArray: true);
    }

    return $tokenString;
  }
}

