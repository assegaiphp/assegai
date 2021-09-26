<?php

namespace LifeRaft\Core\Attributes;

#[\Attribute]
class Get
{
  public function __construct(
    public string $path = '*'
  )
  {
    # Assign values if request is GET
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {

    }
  }
}

?>