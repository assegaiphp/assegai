<?php

namespace LifeRaft\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Controller
{
  public function __construct(
    public string $path = '/',
    public ?string $host = null,
  )
  { }
}

?>