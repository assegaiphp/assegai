<?php

namespace Assegai\Core\Attributes;

use Attribute;

/**
 * The HTTP DELETE request method deletes the specified resource.
 * 
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/DELETE
 */
#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Delete
{
  public array $tokens = [];
  public bool $canActivate = false;
  private mixed $body;

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

    if (!empty($body) && (str_starts_with($body, '{') && str_ends_with($body, '}')))
    {
      $this->body = json_decode($body);
      $this->args['body'] = $this->body;
    }

    $this->canActivate = $request->method() === 'DELETE';
  }
}

