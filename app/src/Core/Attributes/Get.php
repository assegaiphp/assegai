<?php

namespace LifeRaft\Core\Attributes;

use Attribute;

/**
 * The HTTP GET method requests a representation of the specified resource. 
 * Requests using GET should only be used to request data (they shouldn't 
 * include data).
 * 
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/GET
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Get
{
  public array $tokens = [];
  public bool $canActivate = false;

  public function __construct(
    public string $path = '/',
    public array $args = []
  ) {
    global $request;
    $this->tokens = explode('/', trim($path, '/'));
    $requestedURI = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );

    foreach ($this->tokens as $index => $token)
    {
      if (str_starts_with($token, ':'))
      {
        # Get variable at path
        $value = null;

        if (isset($requestedURI[$index + 1]))
        {
          $value = $requestedURI[$index + 1];
        }
        $this->args[trim($token, ':')] = $value;
      }
    }

    $this->canActivate = $request->method() === 'GET';
  }
}

?>