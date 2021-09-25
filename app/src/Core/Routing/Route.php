<?php

namespace LifeRaft\Routing;

class Route
{
  public function __construct(
    protected string $path = '/',
    protected mixed $controller = null 
  )
  {
  }
}

?>