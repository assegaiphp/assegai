<?php

namespace Assegai\Lib\Network;

final class HttpRequestOptions
{
  public function __construct(
    private mixed $body = null,
    private ?array $headers = null, # HttpHeaders
    private ?array $context = null, # HttpContext
    private ?array $params = null, # HttpParams
  ) { }
  
  public function body(): mixed
  {
    return $this->body;
  }
  
  public function headers(): array
  {
    return $this->headers;
  }
  
  public function context(): array
  {
    return $this->context;
  }
  
  public function params(): array
  {
    return $this->params;
  }
}

?>