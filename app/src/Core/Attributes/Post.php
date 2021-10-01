<?php

namespace LifeRaft\Core\Attributes;

use LifeRaft\Core\HttpStatus;
use LifeRaft\Core\HttpStatusCode;

#[\Attribute]
class Post
{
  public array $tokens = [];
  public function __construct(
    public string $path = '',
    public array $args = [],
    public ?HttpStatusCode $status = null
  ) {
    $this->tokens = explode('/', trim($path, '/'));
    $requested_uri = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );
    if (is_null($this->status))
    {
      $this->status = HttpStatus::Created();
    }
    http_response_code($this->status->code());

    foreach ($this->tokens as $index => $token)
    {
      if (str_starts_with($token, ':'))
      {
        # Get variable at path
        $value = null;

        if (isset($requested_uri[$index + 1]))
        {
          $value = $requested_uri[$index + 1];
        }
        $this->args[trim($token, ':')] = $value;
      }
    }
  }
}

?>