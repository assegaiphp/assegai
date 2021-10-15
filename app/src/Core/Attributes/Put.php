<?php

namespace LifeRaft\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class Put
{
  public array $tokens = [];
  public function __construct(
    public string $path = '',
    public array $args = []
  ) {
    $this->tokens = explode('/', trim($path, '/'));
    $requested_uri = explode(separator: '/', string: (isset($_GET['path']) ? $_GET['path'] : '/') );

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