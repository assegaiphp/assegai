<?php

namespace LifeRaft\Core\Attributes;

use \Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Injectable
{
  public function __construct()
  {
    
  }
}

?>