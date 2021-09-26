<?php

namespace LifeRaft\Core;

/**
 * 
 */
class Request
{
  protected mixed $body;
  protected array $headers = [];

  public function __construct(
    public App $app
  ) {
    $this->body = match ($this->method()) {};
    foreach ($_SERVER as $key => $value)
    {

    }
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
}

?>