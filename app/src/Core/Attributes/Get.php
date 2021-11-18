<?php

namespace Assegai\Core\Attributes;

use Attribute;
use stdClass;

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
  private stdClass $body;

  public function __construct(
    public string $path = '/',
    public array $args = []
  ) {
    global $request;
    $this->tokens = explode('/', trim($path, '/'));
    $requestedURI = explode(separator: '/', string: ($_GET['path'] ?? '/') );

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

    $body = $_GET;
    unset($body['path']);
    $exclude = ['limit', 'skip', 'sort', 'orderBy'];

    foreach ($body as $key => $param)
    {
      if (in_array($key, $exclude))
      {
        unset($body[$key]);
        continue;
      }
      $this->args[$key] = $param;
    }

    $body = json_decode( json_encode($body) );
    $this->body = is_array($body) ? new stdClass() : $body;

    if (is_null($this->body))
    {
      $this->body = new stdClass;
    }

    $this->args['body'] = $this->body;
    $this->canActivate = $request->method() === 'GET';
  }
}

