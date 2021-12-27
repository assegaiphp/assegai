<?php

namespace Assegai\Core\Attributes;

use Attribute;
use stdClass;

/**
 * The HTTP PUT request method creates a new resource or replaces a 
 * representation of the target resource with the request payload.
 * 
 * The difference between PUT and POST is that PUT is idempotent: calling 
 * it once or several times successively has the same effect (that is no 
 * side effect), whereas successive identical POST requests may have 
 * additional effects, akin to placing an order several times.
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Put
{
  public array $tokens = [];
  public null|stdClass|array $body = null;
  public bool $canActivate = false;

  public function __construct(
    public string $path = '',
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

    $body = file_get_contents('php://input');
    $this->body = json_decode( $body );

    if (json_last_error() !== JSON_ERROR_NONE)
    {
      $this->body = json_decode(json_encode([]));
    }
    $this->args['body'] = $this->body;

    $this->canActivate = $request->method() === 'PUT';
  }
}

