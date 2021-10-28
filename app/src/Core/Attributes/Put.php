<?php

namespace LifeRaft\Core\Attributes;

use Attribute;
use stdClass;

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

?>