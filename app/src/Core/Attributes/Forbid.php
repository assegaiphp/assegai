<?php

namespace Assegai\Core\Attributes;

use Attribute;

#[Attribute]
class Forbid
{
  public function __construct(
    public array|string $methods = []
  )
  {
    if (is_string($this->methods))
    {
      $this->methods = strtoupper($this->methods);
      $this->methods = explode(',', ($this->methods));
    }
    else
    {
      foreach ($methods as $index => $method)
      {
        $this->methods[$index] = strtoupper($method);
      }
    }
  }
}

?>