<?php

namespace Assegai\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Options
{
  public array $tokens = [];
  private bool $canActivate;

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

    $this->canActivate = $request->method() === 'OPTIONS';
  }
}

